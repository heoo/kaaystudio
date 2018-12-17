<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag,
    Bpai\Models\Acls,
    Bpai\Models\Roles;

/**
 * @Created by PhpStorm.
 * @Date: 15-06-25 16:59
 * @Desc: 权限管理 控制器
 * @Author : 王鹏剑
 */
class AclsController extends ControllerBase
{
    protected $Model;
    protected $Roles;
    protected $reflection;
    protected function initialize()
    {
        parent::initialize();
        Tag::prependTitle('权限管理');
        $this->Model = new Acls();
        $this->Roles = new Roles();
        $this->reflection = new \Bpai\Bizs\Reflection();
    }

    /**
     * @desc 权限管理列表
     * @author 王鹏剑
     * @date 2015-06-26 11:00
     */
    public function listAction()
    {
        $getData = $this->get();
        $getData['page'] = $getData['page']?:1;
        $getData['rows'] = $getData['rows']?:10;
        $result = array();
        $count =  $this->Model->countRec();
        $this->Model->setLimit($getData['rows'],( $getData['page']-1 )*$getData['rows']);
        $this->Model->setOrder(array('created'=>'DESC'));
        $data =  $this->Model->listRec();
        if($data)
        {
            foreach($data->toArray() as $k=>$v)
            {
                $v['rule'] = $this->ruleHandle($v['rule']);
                $v['cstatus'] = $v['status'] ?  '<span style="color:red;">停用</span>' : '<span style="color:green;">启用</span>';

                $result['rows'][$k] = $v;
            }
            $result['total'] = $count;
        }
        $this->view->setVar("data",$result);
        $this->view->setVar("pageStr",$this->_Function->getNewPages($result['total'],$getData['page'],$getData['rows']));

    }

    /**
     * @desc 添加角色权限
     * @author 王鹏剑
     * @date 2015-06-25 17:12
     * */
    public function addAction()
    {
        if($this->isPost())
        {
            $postData = $this->post();
            if(empty($postData['role']))
            {
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'请选择要维护的角色'));exit;
            }

            if( empty($postData['actions']))
            {
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'请选择权限资源'));exit;
            }

            $roleArr = explode('_',$postData['role']);
            $postData['role_code'] = $roleArr[0];
            $postData['role_name'] = $roleArr[1];

            $postData['rule'] = json_encode($postData['actions']);

            $postData['code'] = $this->code();
            $postData['key'] = $this->router->getControllerName().'_'.$postData['code'];
            $postData['created'] = $postData['updated']  = time();
            $postData['createdby'] = $postData['updatedby'] = $this->user['username'];

            if($this->Model->saveRec($postData))
            {
                $this->log('权限添加成功');
                echo json_encode(array('errorNo'=>'00000','errorMsg'=>'权限添加成功'));exit;
            }
            else
            {
                $this->log('权限添加失败');
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'权限添加失败'));exit;
            }
        }
        else
        {
            $this->Roles->setField(array('code','name'));
            $this->view->setVar('rolesObj',$this->Roles->listRec());
        }
    }

    /**
     * @desc 编辑角色权限
     * @author 王鹏剑
     * @date 2015-06-25 17:12
     * */
    public function editAction()
    {
        if($this->isPost())
        {
            $postData = $this->post();
            if( empty($postData['actions']))
            {
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'请选择权限资源'));exit;
            }

//            $roleArr = explode('_',$postData['role']);
//            $postData['role_code'] = $roleArr[0];
//            $postData['role_name'] = $roleArr[1];

            //$postData['rule'] = $this->refArr($postData['controllers'],$postData['actions']);
            $postData['rule'] = json_encode($postData['actions']);

            $postData['updated']  = time();
            $postData['updatedby'] = 'admin';

            $this->Model->setWhere(array('id'=>$postData['id']));
            if($this->Model->saveRec($postData))
            {
                $this->log('权限编辑成功');
                echo json_encode(array('errorNo'=>'00000','errorMsg'=>'权限编辑成功'));exit;
            }
            else
            {
                $this->log('权限编辑失败');
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'权限编辑失败'));exit;
            }
        }
        else
        {
            $this->Model->setWhere(array('code'=>$this->get('code')));
            $this->view->setVar('data',$this->Model->findRec());
            $this->view->setVar('rolesObj',$this->Roles->listRec());
        }
    }

    /**
     * @desc 获取权限资源
     * @author 王鹏剑
     * @date 2015-06-25 17:43
     * */
    public function allAction()
    {
        $result = $this->reflection->getAllController();
        if($this->get('aclsId'))
        {
            $this->Model->setWhere(array('id'=>$this->get('aclsId')));
            $aclRes = $this->Model->findRec();
            if($aclRes)
            {
                $aclsArr = json_decode($aclRes->rule,true);
                foreach($aclsArr as $key=>$val)
                {
                    foreach($val as $ruleK=>$ruleV)
                    {
                        foreach($result[$key]['actions'] as $actionK=>$actionV)
                        {
                            if($ruleV == $actionV['action']){
                                $actionV['check'] = 'checked';
                            }
                            $result[$key]['actions'][$actionK]=$actionV;
                        }
                    }
                }
            }
        }
        echo json_encode($result);exit;
    }


    /**
     * @desc 规则处理
     * @param string $string
     * @author 王鹏剑
     * @date 2015-06-26 14:22
     * */
    public function ruleHandle($string)
    {
        $nameArr = array();
        if($string)
        {
            $result = $this->reflection->getAllController();
            $aclsArr = json_decode($string,true);
            if(is_array($aclsArr))
            {
                foreach($aclsArr as $key=>$val)
                {
                    $str .= $result[$key]['controllerName'].':';
                    foreach($val as $ruleK=>$ruleV)
                    {
                        if(is_array($result[$key]['actions']))
                        {
                            foreach($result[$key]['actions'] as $actionK=>$actionV)
                            {

                                if($ruleV == $actionV['action']){
                                    $str .= $actionV['actionName'].'、';
                                }
                            }

                        }
                    }
                }
            }
        }
        return $str;
    }

    /**
     * @desc 处理权限数组
     * @param array() $arr 要处理的数组
     * @return string  json_encode后的数据;
     */
    public function refArr($controllers,$actions)
    {
        if(is_array($controllers)){
            foreach($controllers as $key=>$val)
            {
                $ruleArr[$key] = $actions[$key];
            }
        }
        return json_encode($ruleArr);
    }
}


<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag,
    Bpai\Models\Roles;

/**
 * @Created by PhpStorm.
 * @Date: 15-5-9
 * @Time: 上午9:09
 * @Desc: 角色管理 控制器
 * @Author : 顾锋伟
 */
class RolesController extends ControllerBase
{
    private $Model;
    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('角色管理');
        $this->Model = new Roles();
    }


    /**
     * @desc 列表数据处理
     * @author 顾锋伟
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
        $data1 =  $this->Model->listRec();
        $data=$data1->toArray();
        if($data){
            foreach($data as $k=>$v){
                if(!empty($v['updated'])){
                    if($v['pid']==0){
                        $v['pid']="顶级角色";
                    }else{
                        $id=$v['pid'];
                        $v['pid'] = "".$data["$id"]['name'];
                    }
                    $v['updated'] = date('Y-m-d H:i',$v['updated']);
                    $v['updatedby'] = 'admin';
                }
                $result['rows'][$k] = $v;
            }
            $result['total'] = $count;
        }
        $this->view->setVar('page',$getData['page']);
        $this->view->setVar("data",$result);
        $this->view->setVar("pageStr",$this->_Function->getNewPages($result['total'],$getData['page'],$getData['rows']));
    }




    /**
     * @desc 创建角色
     * @author 顾锋伟
     * @date 2015-06-12 16:04
     * */
    public function addAction(){
        if($this->isPost()){
            $postData = $this->post();
            $postData['code'] = $this->code();
            $postData['key'] = 'user_'.$postData['code'];
            $postData['created'] = $postData['updated'] = time();
            $postData['name'] = trim($postData['name']);
            $postData['createdby'] = $postData['updatedby'] = $this->user['username'];
            if($this->Model->saveRec($postData)){
                $this->log('角色创建成功');
                echo json_encode(array('errorNo'=>'00000','errorMsg'=>'角色创建成功'));exit;
            }else{
                $this->log('角色创建失败');
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'角色创建失败'));exit;
            }
        }else{

        }
    }

    /**
     * @desc 编辑角色
     * @author 顾锋伟
     * @date 2015-06-12 16:04
     * */
    public function editAction(){
        $getData = $this->get();
        $getData['page'] = $getData['page']?:1;
        if($this->isPost()){
            $postData = $this->post();
            $data['updated'] = $data['created'] = time();
            $data['updatedby'] = $data['createdby'] = $this->user['username'];
            $data['name'] = trim($postData['name']);
            $this->Model->setWhere(array('id'=>$postData['id']));
            $result = $this->Model->saveRec($data);
            if($result){
                $this->log('角色修改成功');
                echo json_encode(array('errorNo'=>'00000','errorMsg'=>'角色修改成功','page'=>$getData['page']));exit;
            }else{
                $this->log('角色修改失败');
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'角色修改失败','page'=>$getData['page']));exit;
            }

        }
        $this->Model->setWhere(array('code'=>$getData['code']));
        $data = $this->Model->findRec();

        $this->view->setVar('data',$data);
        $this->view->setVar('page',$getData['page']);
    }

    /**
     * @desc 删除角色
     * @author 顾锋伟
     * @date 2015-06-12 16:04
     * */
    public function deleteAction(){

        //parent::delAction();
        if($this->isPost()){
            $code=$this->post('code');
            $this->Model->setWhere(array('code'=>$code));
            if($this->Model->delRec()){
                $this->log('角色信息删除成功');
                echo json_encode(array('errorNo'=>'00000','errorMsg'=>'角色信息删除成功'));
            }else{
                $this->log('角色信息删除失败');
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'角色信息删除失败'));
            }
        }
        exit;
    }

}


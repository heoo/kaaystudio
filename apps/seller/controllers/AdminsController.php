<?php
namespace Bpai\Seller\Controllers;
use Bpai\Models\Admins;

/**
 * @desc 管理员管理
 * @date 2017-08-11 17：39
 */
class AdminsController extends ControllerBase
{
    protected $Models;
    /**
     * 初始化设置
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        \Phalcon\Tag::prependTitle('管理员列表');
		$this->Models = new Admins();
    }

    /**
     * @desc 添加管理员
     * @return void get请求显示页面，post请求执行添加
     */
    public function addAction(){

        if($this->isPost()){
            //过滤两边空格（不包含密码）
            $data = $this->post();
            $password = $data['password'];
            $repassword = $data['repassword'];
            unset($data['password'],$data['repassword']);
            if($password == $repassword)
            {
                $data['password'] = $password;
                $data['repassword'] = $repassword;
                $data['code'] = $this->code();
                $data['username'] = $data['username'];
                $data['key'] = $this->router->getControllerName().'_'.$data['code'];
                $data['password'] = $this->security->hash($data['password']);
                $data['status'] = 1;
                $data['role_code'] = '7b8312f30b';
                $data['created'] =  $data["updated"] = time();
                $data['createdby'] = $data["updatedby"] = $this->user['username'];

                if($this->Models->saveRec($data)){
                    echo json_encode(array('errorNo'=>'00000','errorMsg'=>'管理员创建成功'));
                }else{
                    echo json_encode(array('errorNo'=>'00001','errorMsg'=>'管理员创建失败'));
                }
            }
            else
            {
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'两次密码不一致'));
            }
           die;
        }
    }


    /**
     * @desc 编辑管理员
     * @return void get请求显示页面，post请求执行修改
     */
    public function editAction(){

        if($this->isPost()){
            //过滤两边空格
            $data = $this->post();
            $data = $this->trimspace($data);
            $this->Models->setWhere(array("code"=>$data['code']));
            $data['updated'] = time();
            $data['updatedby'] = $this->user['username'];

            if($this->Models->saveRec($data)){
                echo json_encode(array('errorNo'=>'00000','errorMsg'=>'管理员修改成功'));exit;
            }else{
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'管理员修改失败'));exit;
            }
        }

        $getData = $this->get();
        $this->Models->setWhere(array('code'=>$getData['code']));
        $this->view->setVar('data',$this->Models->findRec());
    }



    /**
     * @desc 添加/修改 管理员 数据验证
     * @return bool 验证通过返回true否则返回错误信息
     */
    private function checkData($data){
       // if(!preg_match('/^[a-z0-9_-]{5,15}$/',$data['username'])){exit('管理员账号格式不正确');}
        if(!preg_match('/^[a-z0-9_-]{5,15}$/',$data['username'])){echo json_encode(array('errNo'=>'00001','errorMsg'=>'管理员帐号格式不正确'));exit;}
        $searchData = $this->Models->findFirst(array("conditions"=>"username='{$data['username']}'"));
        if(!$data['code']){
            //添加
            if($searchData){
                //$this->error('管理员账号已经存在');
				echo json_encode(array('errorNo'=>'00001','errorMsg'=>'管理员帐号已经存在'));exit;
            }
            if(!preg_match('/^.{5,}$/',$data['password'])){echo json_encode(array('errNo'=>'00001','errorMsg'=>'密码格式不正确'));exit;}
            if($data['repassword']!==$data['password']){echo json_encode(array('errNo'=>'00001','errorMsg'=>'两次输入的密码不一样'));exit;}
        }else{
            //修改
            $currentData = $this->Models->findFirst(array("conditions"=>"code='{$data['code']}'"));
            if($searchData&&($currentData!=$searchData)){
                //$this->error('管理员账号已经存在');
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'管理员帐号已经存在'));exit;
            }
        }

        if(empty($data['role_code'])){ echo json_encode(array('errNo'=>'00001','errorMsg'=>'请选择一个角色名称'));exit;}
        if(empty($data['name'])){echo json_encode(array('errNo'=>'00001','errorMsg'=>'名字不能为空'));exit;}
        if(empty($data['qq'])){echo json_encode(array('errNo'=>'00001','errorMsg'=>'QQ号码不能为空'));exit;}
        if(!preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/',$data['email'])){
            echo json_encode(array('errorNo'=>'00001','errorMsg'=>'邮箱格式不正确'));exit;
        }

    }

    /**
     * @desc 修改管理员密码
     * @return mixed get请求显示页面，post请求执行修改
     */
    public function resetAction(){
        if($this->isPost())
        {
            $postData = $this->post();
            if(preg_match('/.{6,}/',$postData['password']))
            {
                if($postData['password'] == $postData['repassword'])
                {
                    $this->Models->setWhere(array("code"=>$postData['code']));
                    $res = $this->Models->findRec();
                    if($res)
                    {
                        $res->password = $this->security->hash($postData['password']);
                        $res->updated =time();
                        if($res->update())
                        {
                            $result =array('errorNo'=>'00000','errorMsg'=>'密码修改成功！');
                        }
                        else
                        {
                            $result = array('errorNo'=>'00001','errorMsg'=>'密码修改失败，请联系管理员');
                        }
                    }
                    else
                    {
                        $result = array('errorNo'=>'00001','errorMsg'=>'管理员不存在');
                    }
                }
                else
                {
                    $result = array('errorNo'=>'00001','errorMsg'=>'新密码与确认密码不一致');
                }
            }
            else
            {
                $result = array('errorNo'=>'00001','errorMsg'=>'新密码长度不能少于5个字符');
            }
            echo json_encode($result);exit;
        }

        $getData = $this->get();
        $this->Models->setField(array('code','username'));
        $this->Models->setWhere(array('code'=>$getData['code']));
        $data = $this->Models->findRec();
        $this->view->setVar('data',$data);
    }

	/**
	 * @desc 重置管理员密码
	 * @return void
	 */
    public function repwd(){
    
        $data = $this->Models->findFirst("code='{$this->post('code')}'")->toArray();
        
        //发送邮件
        $serviceBizs = new Service();       
        
        $vercode = substr(md5($data['username']),3,8).base64_encode(time());
        
        $url = "http://{$_SERVER['SERVER_NAME']}/api/public/repassword?module=admin&username={$data['username']}&type=repwd&vercode={$vercode}";
        
        $content = '<b>'.$data['name'].'</b> 您好：<br /><br />
        　　请点击以下链接设置您的新密码，该链接3天内有效。<a href="'.$url.'">'.$url.'</a>';
        
        $serviceBizs->sendmail($data['email'],'宝拍系统管理员密码重置',$content);
        
        echo '<font color="green">重置密码邮件发送成功</font>';
        exit;
    }


}
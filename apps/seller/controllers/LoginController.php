<?php
namespace Bpai\Seller\Controllers;
use Bpai\Models\Admins,
    Bpai\Models\Roles,
    Bpai\Bizs\Service;
use Phalcon\Mvc\View;

/**
 * @desc 管理员管理
 * @package Bpai\Admin\Controllers; 
 * @version 2.0
 */
class LoginController extends ControllerBase
{
    protected $Model;
    private $session_usercode;
    /**
     * 初始化设置
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
	    $this->Model = new Admins();
        $this->session_usercode = $this->user['admins']['code'];
        \Phalcon\Tag::prependTitle('登录');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     *  @desc 登录
     */
    public function loginAction()
    {
        // 检查是否是POST请求
        if ($this->isPost() == true) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $this->Model->setWhere(array('username'=>$username));
            $res = $this->Model->findRec();
            //echo "<pre>";
            //print_r($res->toArray());exit();
            // 验证用户不存在
            if (!$res){
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'用户不存在'));
                die;
            }
            // 验证是否停用
            if ($res->status == 1){
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'该用户已停用'));
                die;
            }
            if (!$this->security->checkHash($password, $res->password)) {
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'管理员密码不正确'));
                die;
            }
            //将用户信息写入SESSION
            $this->_registerSession($res);
            echo json_encode(array('errorNo'=>'00000','errorMsg'=>'登录成功！','seller'=>$this->session->get('admins')['seller']));
            die;
        }
        if($this->session->has('admins')){
            if($this->session->get('admins')['seller'])
            {
                $this->response->redirect('/seller/index',true);
            }
            else
            {
                $this->response->redirect('/seller/index',true);
            }
        }         
    }

    /**
     *  @desc 写入session
     *
     * @param Admins $user
     */
    //private function _registerSession(Admins $user)
    private function _registerSession($user)
    {
        $this->session->set('admins', array(
            'id'  => $user->id,
            'code'=> $user->code,
            'username' => $user->username,
            'username' => $user->username,
            'role_code'=> $user->role_code,
            'seller'=> $user->seller_code
        ));
    }

    /**
     *@desc 退出
     */
    public function logoutAction()
    {
        //销毁session会话
        $this->session->remove('admins');

        //销毁权限导航
        $this->session->remove('navigations');

        //$this->response->redirect('/admin/login/login',true);
        $this->response->redirect('/seller/login/login',true);
    }

    /**
     * @desc 修改管理员密码
     * @return mixed get请求显示页面，post请求执行修改
     */
    public function resetPwdAction(){
        if($this->isPost()){
            $postData = $this->post();
            //验证原密码
            $this->Model->setWhere(array("id"=>$postData['id']));
            $res = $this->Model->findRec();
            if(!$res){
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'管理员不存在'));
                die;
            }
//            if(!$this->security->checkHash($postData['nowpwd'],$res->password)){
//                echo json_encode(array('errNo'=>'00001','errorMsg'=>'当前密码不正确'));
//                die;
//            }
            if(!preg_match('/.{5,}/',$postData['newpwd'])){
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'新密码长度不能少于5个字符'));
                die;
            }
            if($postData['newpwd']!= $postData['connewpwd']){
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'新密码与确认密码不一致'));
                die;
            }

            //修改密码
            $data['password'] = $this->security->hash($postData['newpwd']);
            $data['updated'] =time();
            $this->Model->setWhere(array('id'=>$postData['id']));
            // $res->updatedby=$this->session_usercode;
            if(!$this->Model->saveRec($data)){
                echo json_encode(array('errorNo'=>'00001','errorMsg'=>'密码修改失败，请联系管理员'));
                die;
            }
            echo json_encode(array('errorNo'=>'00000','errorMsg'=>'密码修改成功！'));
            die;
        }
        $this->view->setMainView('');
        $getData = $this->get();
        $this->Model->setWhere(array('id'=>$getData['id']));
        $data = $this->Model->findRec();
        $this->view->setVar('data',$data);
    }
}
<?php
namespace Bpai\Plugins;
use Phalcon\Events\Event,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Acl;

/**
 * @desc 模块访问权限控制
 */
class SecurityPlugin extends Plugin
{

    /**
     * @desc 定义模块名称
     * */
   protected  $moduleName;

    /**
     * @desc 定义当前用户信息
     * */
    protected  $user;

    /**
	 * @desc 初始化设置
	 * @return void
	 */
	public function __construct($di)
    {
		//设置当前DI
        $this->_dependencyInjector = $di;
        $this->moduleName = $this->router->getModuleName();

        /**
         * @desc 获取当前登陆用户
         * */
        if( in_array($this->moduleName,array('seller')) )
        {
            $this->user = $this->session->get('admins');
        }
        else
        {
            $this->user = $this->session->get('user');
        }
 
        if(empty($this->user) && $this->router->getControllerName()!='login' && $this->router->getActionName()!='login'){
            header("Location:/".$this->router->getModuleName()."/login/login");
        }

    }

    /**
     * @desc 设置当前用户权限
     * */
    public function setAcls()
    {
        $publicResources = array(
            'index'=>array('index'),
            'login' => array('login','logout','resetPwd','edit','error'),
            'error' => array('index')
        );

        if ( !isset($this->persistent->acls) || $this->user)
        {

            $acl = new \Phalcon\Acl\Adapter\Memory();
            $acl->setDefaultAction(\Phalcon\Acl::DENY);

            //如果用户已经登录，并且角色ID存在
            if(isset($this->user) && !empty($this->user['role_code'])){

                //设置角色名：role_id_code
                $userRole = 'role_id_'.$this->user['role_code'];

                //添加角色
                $roles[$userRole]  = new \Phalcon\Acl\Role($userRole);
                $acl->addRole($roles[$userRole]);

                //给当前角色设置可以访问的资源
                $aclsModels = new \Bpai\Models\Acls();
                $aclsModels->setWhere(array('role_code'=>$this->user['role_code'],'status'=>0));
                $aclsRes = $aclsModels->findRec();

                //私有资源
                $privateResources = json_decode($aclsRes->rule,true);
                foreach($privateResources as $key=>$val)
                {
                    $navArr[] = $key;
                }

                // 左侧权限导航
                $this->session->set('navigations',$navArr);
                  foreach (array_merge(json_decode($aclsRes->rule,true),$publicResources) as $resource => $actions)
                {
                    $acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
                          $acl->allow($userRole, $resource, $actions);
                 }
            }

                //公共角色(给予外部访问权限)
                $roles['guests']    = new \Phalcon\Acl\Role('guests');
                $acl->addRole($roles['guests']);


                foreach ($publicResources as $resource => $actions)
                {
                    $acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
                     $acl->allow('guests',$resource,$actions);
                }

             $this->persistent->acls = $acl;
        }
        return $this->persistent->acls;
    }

    /**
     * @desc 执行之前执行在任何操作在应用程序中
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        /**
        * @desc 未登录跳转到登录页面
        * */
        if(empty($this->user) && $action!='login' && $action!='error')
        {
            $this->response->redirect('/'.$this->router->getModuleName().'/login/login',true);
         }
        if($this->user['role_code'] == '7b8312f30b'){
            return true;
        }

        if($this->user )
        {
            $role = 'role_id_'.$this->user['role_code'];
        }
        else
        {
            $role = 'guests';
        }

        $acls = $this->setAcls();
        $allowed = $acls->isAllowed($role, $controller, $action);



        if ($allowed != Acl::ALLOW) {

            echo json_encode(array('errorNo'=>55204,'errorMsg'=>'当前用户没有权限访问该功能'));exit;
            echo '当前用户没有权限访问该功能';exit;
            $dispatcher->forward(
                array(
                    'controller' => 'error',
                    'action' => 'index'
                )
            );
            return false;
        }
    }

}

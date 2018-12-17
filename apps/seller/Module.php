<?php
namespace Bpai\Seller;
use Phalcon\Loader;				//自动加载
use Phalcon\Mvc\View;			//视图
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;		//数据库服务
use Phalcon\Mvc\ModuleDefinitionInterface;		//定义模块接口
use Phalcon\Http\Response\Cookies;

class Module implements ModuleDefinitionInterface
{
    /**
     * 自动加载
     */
    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = NULL)
    {

        $loader = new Loader();
		
		//自动加载公共库文件夹
        $loader->registerDirs(
            array(
                __DIR__ . '/../apps/library/'
            )
        );
		//加载并配置ADMIN模块相关命名空间
        $loader->registerNamespaces(array(
            'Bpai\Seller\Controllers'		=>	__DIR__ . '/controllers/',
            'Bpai\Models'			=>	__DIR__ . '/../models',
            'Bpai\Plugins'			=>	__DIR__ . '/../library/Plugins',
            'Bpai\Bizs'	     			=>	__DIR__ . '/../bizs',
            'Catchtech\Extensions\Model'	=>	__DIR__ . '/../library/Catchtech/Extensions/Model',
        ));
 
        $loader->register();
    }

    /**
     * 注册模块服务
     *
     * @param \Phalcon\DI $di
     */
    public function registerServices(\Phalcon\DiInterface $di)
    {
        /**
         * 读取ADMIN模块配置
         */
        $config = include __DIR__ . "/config/config.php";



        /**
         *配置分发服务
         */
        $di['dispatcher'] = function() use ($di) {

            //设置事件管理器
            $eventsManager = $di->getShared('eventsManager');
            $security = new \Bpai\Plugins\SecurityPlugin($di);

            //----------------------执行任何控制器任何操作之前的操作   开始---------------------//

            $security = new \Bpai\Plugins\SecurityPlugin($di);

            $eventsManager->attach('dispatch', $security);

            //----------------------执行任何控制器任何操作之前的操作   结束---------------------//

            //----------------------错误处理   开始---------------------//
            $eventsManager->attach('dispatch:beforeException',function($event,$dispatcher,$exception){

                $plugin = new \Phalcon\Mvc\User\Plugin;

                //404异常处理
                if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {

                    $plugin->flashSession->error('404，未找到相关页面');
                    $dispatcher->forward(array(
                        'controller' => 'error',
                        'action' => 'index'
                    ));
                    return false;
                }

                //自定义异常消息处理
                if ($exception instanceof \Phalcon\Exception) {
                    if($exception->getCode()!=0&&$exception->getCode()<=1000){
                        $plugin->flashSession->error($exception->getMessage());
                        $dispatcher->forward(array(
                            'controller' => 'error',
                            'action' => 'index'
                        ));
                        return false;
                    }
                }

                //其他异常处理
                $plugin->flashSession->error('程序异常，请联系管理员');
                $dispatcher->forward(array(
                  'controller' => 'error',
                  'action' => 'index'
                ));

            });

            //----------------------错误处理   结束---------------------//


            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace("Bpai\Seller\Controllers\\");
            return $dispatcher;
        };

		
        /**
         * 设置视图组件
         */
        $di['view'] = function () {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');

            return $view;
        };

		
        //设置flash消息
        $di['flash'] =function() {
            return new \Phalcon\Flash\Session();
        };

        //注册Volt模板引擎
        $di['view'] =function() {

            $view = new \Phalcon\Mvc\View();

            $view->setViewsDir('../apps/seller/views/');

            $view->registerEngines(array(
                ".html" => 'volt'
            ));
            return $view;
        };

		
        /**
         * 设置视图缓存文件夹
         */
        $di['volt'] = function($view, $di) {

            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

            $volt->setOptions(array(
                "compiledPath" => "../cache/seller/"
            ));

            return $volt;
        };

        /**
         * 注册数据库监听
         */
        $di['profiler'] = function() {
            return new \Phalcon\Db\Profiler();
        };
        /**
         * 设置数据库
         */
        $di['db']= function() use ($config,$di){
            $eventsManager = new \Phalcon\Events\Manager();

            //从di中获取共享的profiler实例
            $profiler = $di->getProfiler();

            //监听所有的db事件
            $eventsManager->attach('db', function($event, $connection) use ($profiler) {
                //一条语句查询之前事件，profiler开始记录sql语句
                if ($event->getType() == 'beforeQuery') {
                    $profiler->startProfile($connection->getSQLStatement());
                }
                //一条语句查询结束，结束本次记录，记录结果会保存在profiler对象中
                if ($event->getType() == 'afterQuery') {
                    $profiler->stopProfile();
                }
            });

            $connection  =  new DbAdapter(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname,
                'charset' => $config->database->charset,
            ));
            //将事件管理器绑定到db实例中
            $connection->setEventsManager($eventsManager);

            return $connection;
        };


        $di['cookies'] = function() use ($config){
            $cookies = new \Phalcon\Http\Response\Cookies();
            $cookies->useEncryption(false);
            return $cookies;
        };
  
        $di['crypt'] = function() use ($config){
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey($config->cookieSecret);
            return $crypt;
        };
    }
}

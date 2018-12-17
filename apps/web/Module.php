<?php
namespace Bpai\Web;
use Phalcon\Loader;				//自动加载
use Phalcon\Mvc\View;			//视图
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;		//数据库服务
use Phalcon\Mvc\ModuleDefinitionInterface;		//定义模块接口

use Phalcon\Cache\Frontend\Data as DataFrontend;
use Phalcon\Cache\Backend\File as FileCache;
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
            'Bpai\Web\Controllers'   => __DIR__ . '/controllers/',
            'Bpai\Models'            => __DIR__ . '/../models',
            'Bpai\Plugins'			=>	__DIR__ . '/../library/Plugins',
            'Catchtech\Extensions\Model'  =>  __DIR__ . '/../library/Catchtech/Extensions/Model',
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
        $di['config'] = $config;
		
        /**
         *配置分发服务
         */
        $di['dispatcher'] = function() use ($di) {

            //设置事件管理器
            $eventsManager = $di->getShared('eventsManager');

            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace("Bpai\Web\Controllers\\");
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

            $view->setViewsDir('../apps/web/views/');

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
                "compiledPath" => "../cache/web/"
            ));

            return $volt;
        };

        /**
         * The URL component is used to generate all kind of urls in the application
         */
        $di['url']= function() use ($config){
            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri($config->application->baseUri);
            return $url;
        };

        /**
         * 设置数据库
         */
        $di['db']= function() use ($config){
            return new DbAdapter(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname,
            	'charset' => $config->database->charset,
            ));
        };


        $di->set("filecache",function(){
            $frontend = new DataFrontend(array(
                "lifetime" => 3600
            ));    
            $cache = new FileCache($frontend, array(
                "prefix"   => 'cache',
                "cacheDir" => __DIR__."/../../cache/file/"
            ));
            return $cache;
        });
    }

}

<?php
use Phalcon\Http\Response\Cookies;

//error_reporting(E_ALL ^E_WARNING ^ E_NOTICE);
error_reporting(E_ALL);
//$debug = new \Phalcon\Debug();
//$debug->listen();
try {
	/**
	 * 所有服务注册
	 */

	/**
	 * FactoryDefault依赖注入一个完整的堆栈框架
	 */
	$di = new Phalcon\DI\FactoryDefault();

    require '../vendor/autoload.php';


    /**
	 * 配置路由
	 */
	$di['router'] = require __DIR__.'/../config/routes.php';


	
	/**
	 * 配置SESSION会话服务
	 */
	$di['session'] = function () {
		$session = new Phalcon\Session\Adapter\Files();
		$session->start();

		return $session;
	};
	
	/**
	 * 配置Cookie会话服务
	 */
	$di['cookies'] = function () {
		$cookies = new Cookies();

	    $cookies->useEncryption(false);
	
	    return $cookies;
	};
	/*
	$errors = require __DIR__.'/../config/error.php';
	//$di->setShared('errors', $errors);
	
	$di['errors'] = function () use ($errors){
		
		return $errors;
	};
	*/

	/**
     * 配置项目
     */
    $application = new Phalcon\Mvc\Application();
	
    $application->setDI($di);

	
	
     /**
	 *配置应用模块
	 */
	$application->registerModules(array(
        'seller' => array(
            'className' => 'Bpai\seller\Module',
            'path' => __DIR__ . '/../apps/seller/Module.php'
        ),
		'web' => array(
			'className' => 'Bpai\Web\Module',
			'path' => __DIR__ . '/../apps/web/Module.php'
		)
	));

	

	//输出页面
    echo $application->handle()->getContent();

	
} catch (Phalcon\Exception $e) {

	//获取Phalcon报错
	echo $e->getMessage();
	
} catch (PDOException $e) {

	//获取PDO报错
	echo $e->getMessage();

}

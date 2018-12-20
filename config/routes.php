<?php
$router = new \Phalcon\Mvc\Router; 
$router->setDefaultModule("web");//设置默认访问模块
/*全局路由规则*/
$router->add("/:module/:controller/:action/:params",array(
		'module' => 1,
		"controller" => 2,
		"action" => 3,
		'params' => 4
));
/*缺省控制器、方法*/
$router->add("/:module",array(
    'module' => 1,
    "controller" => "index",
    "action" => 'index',
));

/*缺省方法*/

$router->add("/:module/:controller",array(
        'module' => 1,
        "controller" => 2,
        "action" => 'index',
));
return $router;

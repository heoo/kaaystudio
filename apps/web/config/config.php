<?php
return new \Phalcon\Config(array(
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'port'	   => 3306,
        'username' => 'root',
        'password' => 'password-for@mysql',
        'dbname'   => 'kaay',
        'charset'  => 'UTF8',
    ), 
    'application' => array(
        'controllersDir'=> __DIR__ . '/../controllers/',
        'modelsDir'     => __DIR__ . '/../../models/',
        'pluginsDir'    => __DIR__ . '/../../library/plugins/',
        'viewsDir'      => __DIR__ . '/../views/',
        'baseUri'       => '/'
    ), 
));

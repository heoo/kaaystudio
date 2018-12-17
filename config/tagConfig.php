<?php
return array(
    'adminName'=>'内容管理系统',
    'webName'=>'',
    'domain'=>'http://'.$_SERVER['SERVER_NAME'],
    'image_host'=>'http://duoi.com',
    'uploadFiles'=>array('jpg','jpeg','png','gif','flv'),
    'categoryType'=>array(
        'posts'=>'文章模型',
        'page'=>'单页模型',
//        'url'=>'外部链接',
    ),
    'linksType'=>array(
        1=>'综合网站',
        2=>'社交娱乐',
        3=>'生活服务'
    ),
    'bannersClient'=>array(
        'pc'=>'PC端',
        'app'=>'App端',
    ),
    'bannersLocation'=>array(
        'index'=>'首页',
        'list'=>'栏目页',
        'info'=>'内容页'
    ),
    'en'=>array(
        ''=> 'Index',
        'index'=> 'Index',
        'posts'=> 'posts',
        'about'=> 'About Us',
        'contact'=> 'Contact Us',
        'error'=> 'Pages not found',
        'next'=> 'next',
        'previous'=>'previous',
    ),
    'zh'=>array(
        ''=> '首页',
        'index'=> '首页',
        'posts'=> '详情',
        'about'=> '关于我们',
        'contact'=> '联系我们',
        'error'=> '页面没找到',
        'next'=> '下一张',
        'previous'=>'上一张',
    )

);
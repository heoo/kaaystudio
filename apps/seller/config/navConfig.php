<?php
return array(
    array(
        'name'=>'管理员管理',
        'controller'=>'admins',
        'icons'=>'am-icon-user',
        'element'=>
            array(
                'admins'=>'管理员列表|/seller/admins/list',
            ),
    ),
    array(
        'name'=>'栏目管理',
        'controller'=>'category',
        'icons'=>'am-icon-book',
        'element'=>
            array(
                'category'=>'栏目列表|/seller/category/list',
            ),
    ),
    array(
        'name'=>'内容管理',
        'controller'=>'posts',
        'icons'=>'am-icon-list-alt',
        'element'=>
            array(
                'posts'=>'',
            ),
    ),
    array(
        'name'=>'友链管理',
        'controller'=>'links',
        'icons'=>'am-icon-link',
        'element'=>
            array(
                'links'=>'友链列表|/seller/links/list',
            ),
    ),
    array(
        'name'=>'广告管理',
        'controller'=>'banners',
        'icons'=>'am-icon-volume-up',
        'element'=>
            array(
                'banners'=>'广告列表|/seller/banners/list',
            ),
    ),
    array(
        'name'=>'系统管理',
        'controller'=>'system',
        'icons'=>'am-icon-cog',
        'element'=>
            array(
                'system'=>'站点设置|/seller/system/edit?code=b9cfe8f4bb',
            ),
    ),
//    array(
//        'name'=>'CDN设置',
//        'controller'=>'cdn',
//        'icons'=>'am-icon-cloud',
//        'element'=>
//            array(
//                'cdn'=>'CDN设置|/seller/cdn/edit?code=b9cfe8f4bb',
//            ),
//    ),
);

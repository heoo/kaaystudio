<?php
namespace Bpai\Seller\Controllers;

/**
 * @Desc 异常处理控制器
 * @Created by PhpStorm.
 * @Time: 上午11:55
 * @Author：王鹏剑
 */
class ErrorController extends ControllerBase
{

    public function initialize(){
        parent::initialize();
        \Phalcon\Tag::prependTitle('页面丢失');
    }
    /**
     * @Desc 异常处理首页
     * @Time: 上午11:55
     * @Author：王鹏剑
     */
    public function indexAction(){
        $this->view->setMainView('');
        $this->view->pick('/public/error');
    }
} 
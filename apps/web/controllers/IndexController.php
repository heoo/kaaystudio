<?php
namespace Bpai\Web\Controllers;

/**
 * @desc 首页
 * @date 2015-07-16 14:46
 * */
class IndexController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        \Phalcon\Tag::prependTitle($this->System['web_name']);

    }
    
    public function indexAction()
    {
        $data = $this->getPosts(0,4);
        $this->view->setVar('data',$data);
    }
 }
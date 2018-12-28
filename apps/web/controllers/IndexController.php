<?php
namespace Bpai\Web\Controllers;
use Bpai\Models\Category;

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
        $data = [];
        $models = new  Category();
        $models->setWhere(array('status'=>1));
        $models->setOrder(array('listorder'=>'DESC'));
        $res = $models->listRec();
        if($res){
            $data = $res->toArray();
        }
        echo '<pre>';var_dump($data);exit;
        $this->view->setVar('data',$data);
    }
 }
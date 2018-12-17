<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;
use Bpai\Models\Posts,
    Bpai\Models\Category;
/**
 * @desc 后台首页
 * */
class IndexController extends ControllerBase
{
    protected $Posts;
    protected $Category;
    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('首页');
        $this->Posts = new Posts();
        $this->Category = new Category();
    }
    /**
     * @desc 后台首页
     * */
    public function indexAction()
    {
        $env['sapiName'] =  $_SERVER['SERVER_SOFTWARE'];
        $env['phpversion'] = phpversion();

        $phalcon = new \ReflectionExtension('phalcon');
        $env['phalcon'] = $phalcon->getVersion();

        $env['max'] = ini_get('upload_max_filesize');
        $this->view->setVar('env',$env);

        $posts['count'] = $this->Posts->countRec();

        $this->Posts->setField(array('code','name','hits'));
        $this->Posts->setWhere(array('status'=>1));
        $this->Posts->setOrder(array('hits'=>'DESC'));
        $posts['hits']  = $this->Posts->findRec();

        $this->Posts->setField('hits');
        $posts['sum']  = $this->Posts->sumRec();

       $posts['category'] = $this->Category->countRec();

        $this->view->setVar('posts',$posts);
    }
}
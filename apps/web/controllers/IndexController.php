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
        $models->setOrder(array('listorder'=>'ASC'));
        $res = $models->listRec();
        if($res){
            foreach ($res->toArray() as $val){
                 if($this->checkLanguage()){
                    $val['name'] = $val['en_name'];
                    $val['text'] = $val['en_text'];
                    $val['more'] = $val['en_more'];
                }
                $val['text'] = htmlspecialchars_decode($val['text']);
                $val['url'] = '';
                if( in_array($val['type'],array('posts','images'))){
                    $val['url'] = "http://{$_SERVER['HTTP_HOST']}/{$this->router->getModuleName()}/posts/index?cid={$val['id']}";
                }elseif($val['type'] == 'page') {
                    $val['url'] = "http://{$_SERVER['HTTP_HOST']}/{$this->router->getModuleName()}/details/index?id={$val['val']}";
                }elseif($val['type'] == 'url'){
                    $val['url'] = $val['val'];
                }
                $data[] = $val;
            }
        }
//                echo '<pre>';var_dump($data);exit;

        $this->view->setVar('data',$data);
//        $this->view->pick('index/main');
    }
 }
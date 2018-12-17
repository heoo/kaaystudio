<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
use Bpai\Models\Posts;

/**
 * @desc 首页
 * @date 2015-07-16 14:46
 * */
class IndexController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        \Phalcon\Tag::prependTitle($this->_TagConfig[$this->router->getControllerName()]);

    }
    
    public function indexAction()
    {
        $Models = new Posts();
        $Models->setField(array('id')); 
        $Models->setWhere(array('status'=>1,'language'=>$this->Language,'type'=>'posts','attachment'=>array('attachment','!=','')));
        $Models->setOrder(array('id'=>'DESC'));
        $res = $Models->findRec();
        $this->view->setVar('next',$res ? $res->id : 0);

        $result = $this->getPosts();
        if($result){
            foreach($result as $key=>$val){
//                $val['name'] = self::strManipulation($val['name']);
                $result[$key] = $val;
            }
        }
        $this->view->setVar('data',$result);
    }

    protected function strManipulation($str){
        $string = '';
        if($str){
            $len = mb_strlen($str);
            for($i = 0 ;$i < $len ; $i++){
                $tmp = mb_substr($str,$i,1);
                $string .= "<em>{$tmp}</em>";
                unset($tmp);
            }
        }
        return $string;
    }

 }
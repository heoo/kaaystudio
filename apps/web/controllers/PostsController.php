<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
use Bpai\Models\Posts;
class PostsController extends ControllerBase {

    public $Models;
	public function initialize() {
		parent::initialize();
		Tag::prependTitle($this->_TagConfig[$this->router->getControllerName()]);
		$this->Models = new Posts();
	}
	
	public function indexAction() {

        Tag::prependTitle('列表');

        if($this->isMobile()){
 
            $this->Models->setWhere(array('language'=>$this->Language,'status'=>1,'attachment'=>array('attachment','!=',''),'cid'=>intval($this->get('cid'))));
            $this->Models->setOrder(array('id'=>'DESC'));
            $res = $this->Models->findRec();
            if($res){

                Tag::prependTitle($res->name.'-');
                if($res->attachment){

                    $attachment = explode(',',$res->attachment);
                }

                $this->view->setVar('data',$res);
                $this->view->setVar('attachment',$attachment);

                $this->view->setVar('count',count($attachment));
                $this->view->setVar('next',$this->getNearId($res->id));

                $this->view->setVar('previous',$this->getNearId($res->id,'previous'));

                $this->view->pick('posts/details-wap');

            }else{

                $this->response->redirect("/{$this->router->getModuleName()}/error",true);
            }

        }else{

            $this->view->setVar('data',$this->getPosts($this->get('cid')));
            $this->view->pick('index/index');
        }
	}

    public function detailsAction(){

        $this->Models->setWhere(array('id'=>$this->get('id'),'language'=>$this->Language,'status'=>1,'attachment'=>array('attachment','!=','')));
        $res = $this->Models->findRec();
        if($res){

            Tag::prependTitle($res->name.'-');
            if($res->attachment){

                $attachment = explode(',',$res->attachment);
            }

            $this->view->setVar('data',$res);
            $this->view->setVar('attachment',$attachment);

            $this->view->setVar('count',count($attachment));
            $this->view->setVar('next',$this->getNearId($res->id));

            $this->view->setVar('previous',$this->getNearId($res->id,'previous'));

            if($this->isMobile()){
                $this->view->pick('posts/details-wap');
            }
        }else{

            $this->response->redirect("/{$this->router->getModuleName()}/error",true);
        }
    }

    public function iframeAction(){
        $this->view->setTemplateAfter('null');

    }
	
}

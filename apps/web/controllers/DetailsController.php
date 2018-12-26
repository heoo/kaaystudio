<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
use Bpai\Models\Posts;
class DetailsController extends ControllerBase {

    public $Models;
	public function initialize() {
		parent::initialize();
		$this->Models = new Posts();
	}
	
	public function indexAction() {
        $this->Models->setWhere(array('id'=>$this->get('id')));
        $result = $this->Models->findRec();
        if($result){
            $data = $result->toArray();
        }
        Tag::setTitle($data['name']);

        if($data['cid']){
            $category = self::getCategory($data['cid']);
            if($category){
                $this->view->setVar('category',$category);
                Tag::setTitle($data['name'].'-'.$category['name']);
            }
        }
        $data['text'] = htmlspecialchars_decode($data['text']);
        $data['time'] = date('Y-m-y H:i',$data['created']);

        self::setPostsHits($data['id']);
        $this->view->setVar('data',$data);

        $this->Models->setField(array('id','name'));
        $this->Models->setWhere(array('status'=>1,'cid'=>$data['cid'],array('id','<',$data['id'])));
        $prevRes = $this->Models->findRec();
        $this->view->setVar('prev',$prevRes);

        $this->Models->setField(array('id','name'));
        $this->Models->setWhere(array('status'=>1,'cid'=>$data['cid'],array('id','>',$data['id'])));
        $nextRes = $this->Models->findRec();
        $this->view->setVar('next',$nextRes);
	}

}

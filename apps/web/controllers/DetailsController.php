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


        $more = $this->getPosts(0,'id',4);
        $this->view->setVar('more',$more);

        $right = $this->getPosts(0,'hits',8);
        $this->view->setVar('right',$right);
	}

}

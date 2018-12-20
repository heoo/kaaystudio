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

        $this->view->setVar('data',$data);
	}

}

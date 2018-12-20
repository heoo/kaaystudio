<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
use Bpai\Models\Posts;
use Bpai\Plugins\numPage;
class PostsController extends ControllerBase {

    public $Models;
	public function initialize() {
		parent::initialize();
		Tag::prependTitle($this->_TagConfig[$this->router->getControllerName()]);
		$this->Models = new Posts();
	}
	
	public function indexAction() {
        $getData = $this->get();
        $getData['page'] =  $getData['page'] ?  $getData['page'] : 1;
        $getData['rows'] = $getData['rows'] ? $getData['rows'] : 12;

        if($getData['cid']){
            $category = self::getCategory($getData['cid']);
            $this->view->setVar('category',$category);
            Tag::setTitle($category['name']);
        }
        $data = $this->getPosts($getData['cid'],$getData['rows'],($getData['page']-1)*$getData['rows']);
        $this->view->setVar('data',$data);
        $this->view->setVar('getData',$getData);
        $myPage=new numPage($this->getPostsCount($getData['cid']),intval($getData['page']),$getData['rows']);
        $pageStr= $myPage->GetPagerContent();
        $this->view->setVar('pages',$pageStr);
	}

}

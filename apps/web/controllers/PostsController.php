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
        $getData = $this->get();
        $getData['page'] =  $getData['page'] ?  $getData['page'] : 1;
        $getData['rows'] = $getData['rows'] ? $getData['rows'] : 10;

        $data = $this->getPosts($this->get('cid'),$getData['rows'],($getData['page']-1)*$getData['rows']);
        $this->view->setVar('data',$data);
	}

}

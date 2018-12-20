<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
use Bpai\Models\Posts;
class DetailsController extends ControllerBase {

    public $Models;
	public function initialize() {
		parent::initialize();
		Tag::prependTitle($this->_TagConfig[$this->router->getControllerName()]);
		$this->Models = new Posts();
	}
	
	public function indexAction() {
        Tag::prependTitle('列表');
	}

}

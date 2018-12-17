<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;
use Bpai\Models\Category;

class CategoryController extends ControllerBase
{
    protected $Models;

    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('栏目列表');
        $this->Models = new Category();
    }

}


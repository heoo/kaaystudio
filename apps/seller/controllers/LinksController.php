<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;
use Bpai\Models\Links;

class LinksController extends ControllerBase
{
    protected $Models;

    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('友链管理');
        $this->Models = new Links();
    }

}


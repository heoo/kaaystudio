<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;
use Bpai\Models\Banners;

class BannersController extends ControllerBase
{
    protected $Models;

    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('广告管理');
        $this->Models = new Banners();
    }

}


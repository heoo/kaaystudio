<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;
use Bpai\Models\System;

class SystemController extends ControllerBase
{
    protected $Models;

    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('站点设置');
        $this->Models = new System();
    }

}


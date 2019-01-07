<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;
use Bpai\Models\System;

class CdnController extends ControllerBase
{
    protected $Models;

    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('CDN设置');
        $this->Models = new System();
    }

}


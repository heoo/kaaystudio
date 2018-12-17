<?php

namespace Bpai\Bizs;
use Bpai\Models\Category;

class commonBizs {

    private $CategoryModels;

    public function __construct(){

        $this->CategoryModels = new Category();
    }

    public function navigation(){
    }
}
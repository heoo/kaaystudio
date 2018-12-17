<?php
/**
 * 账户密钥生成类
 * 
 * 用于生成账户密钥
 * @author haowei
 *
 */
namespace Catchtech\Extensions\Guid {

    class Salt extends \Catchtech\Extensions\Guid\GuidAbstract {

        public function genIdentity() {
            // TODO: Auto-generated method stub
            return substr(md5(microtime()),0,6);
        }

    }
}


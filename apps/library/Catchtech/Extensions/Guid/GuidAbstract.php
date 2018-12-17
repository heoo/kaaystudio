<?php

/**
 * Guid生成类
 * 
 * 用来派生各种Guid
 * 
 * @abstract
 * @author 郝巍
 * @version 1.0
 */
namespace Catchtech\Extensions\Guid {

    abstract class GuidAbstract implements \Catchtech\Interfaces\iObject {

        /**
         * 导入公共方法
         */
        use \Catchtech\Extensions\Traits\MagicGeneral;


        /**
         * 生成Guid的方法
         *
         * @abstract
         * @todo 需要在子类中实现
         */
        abstract public function genIdentity();


    }

	
}

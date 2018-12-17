<?php
/**
 * 上传文件命名规则的抽象类
 * @author 郝巍
 * @version 1.0
 *
 */
namespace Catchtech\Services\Uploaders\Namepolicys{
    abstract class NamepolicyAbstract implements \Catchtech\Interfaces\ObjectInterface{

        use \Catchtech\Interfaces\iObjectImpelemetion;

        abstract public function getName(Array $option);

    }
}

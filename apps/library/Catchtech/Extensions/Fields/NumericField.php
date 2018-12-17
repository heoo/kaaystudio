<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:05
 * 数字选择框
 */

namespace Catchtech\Extensions\Fields;


class NumericField  extends \Phalcon\Forms\Element
{
    public function render($attributes=null)
    {
        $html = '<input class="easyui-numberspinner" value="'.$this->getAttribute('value').'" name="'.$this->getName().'" data-options="min:1" style="width:80px;">';
        return $html;
    }
} 
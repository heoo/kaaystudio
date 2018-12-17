<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:26
 * 日期时间选择框
 */

namespace Catchtech\Extensions\Fields;


class DateField extends \Phalcon\Forms\Element
{
    public function render($attributes=null)
    {
        $html = '<input class="easyui-datebox" name="'.$this->getName().'" value="'.$this->getAttribute('value').'"  required="required">';
        return $html;
    }
} 
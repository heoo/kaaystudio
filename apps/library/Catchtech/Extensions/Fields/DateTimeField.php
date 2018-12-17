<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:40
 * 日期+时间选择框（ui）
 */

namespace Catchtech\Extensions\Fields;


class DateTimeField  extends \Phalcon\Forms\Element
{
    public function render($attributes=null)
    {
        $html = '<input class="easyui-datetimebox"   name="'.$this->getName().'" value="'.$this->getAttribute('value').'"  style="width:150px">';
        return $html;
    }
}
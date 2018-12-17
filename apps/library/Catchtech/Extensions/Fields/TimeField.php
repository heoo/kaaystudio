<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:40
 * 时间选择框（ui）
 */

namespace Catchtech\Extensions\Fields;


class TimeField  extends \Phalcon\Forms\Element
{
    public function render($attributes=null)
    {
        $html = '<input class="easyui-timespinner" value="'.$this->getAttribute('value').'" name="'.$this->getName().'" style="width:80px;"></input>';
        return $html;
    }
} 
<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:28
 * 复选框
 */

namespace Catchtech\Extensions\Fields;


class CheckField extends \Phalcon\Forms\Element
{
    protected $_text;
    public function __construct($name,$text,$attributes=null){
        $this->_text = $text;
        parent::__construct($name,$attributes);
    }

    public function render($attributes=null){
        $html = '<input type="checkbox" name="'.$this->getName().'" value="'.$this->getAttribute('value').'" '.$this->getAttribute('checked').'> &nbsp;&nbsp;'.$this->_text;
        return $html;
    }
}
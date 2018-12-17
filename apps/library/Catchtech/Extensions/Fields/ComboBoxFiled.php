<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:34
 * 下拉复选框（优点省空间）
 */

namespace Catchtech\Extensions\Fields;


class ComboBoxFiled extends \Phalcon\Forms\Element {

    protected $_code;
    public function __construct($name,$code,$attributes=null){
        $this->_code = $code;

        parent::__construct($name,$attributes);
    }


    public function render($attributes=null){
        //print_r($this->optionAct());exit;

        $html = '<input class="easyui-combobox" name="'.$this->getName().'[]"   data-options=" url:\'/admin/customrender/checklist?value='.$this->getAttribute('value').'&code='.$this->_code.'\',method:\'get\', valueField:\'id\', textField:\'text\',
					multiple:true,panelHeight:\'auto\'">';
        return $html;
    }

}


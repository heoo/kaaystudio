<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:34
 * 下拉复选框（优点省空间）
 */

namespace Catchtech\Extensions\Fields;


class QuoteRadioFiled extends \Phalcon\Forms\Element {

    protected $_styles;
    public function __construct($name,$styles,$attributes=null){
        $this->_styles = $styles;
        parent::__construct($name,$attributes);
    }

    /*
     * array(
     *      'cname'     => '名字'，
     *      'src'       => '/sss/sss/ss.jpg',
     *      'value'     => 'csd656w2'
     * );
     *  <!--src="'.$style['src'].'"-->
     */
    public function render($attributes=null)
    {
        $j=1;
        $html = '';
        foreach($this->_styles as $style)
        {
            if($this->getAttribute('onClick'))
            {
                $onclick = 'onclick = "'.$this->getAttribute('onClick').'"';
            }

            if($this->getAttribute('value')==$style['value'])
            {
                $isChecked = 'checked="checked"';
            }
            else
            {
                $isChecked = '';
            }
            $id = time().rand(10000,99999);
//            var_dump($style);echo "<br>".var_dump($this->getAttribute('value'));exit;
            /*src="/sys/images/calculator/type70.png"*/
            $html .= '<a class="pl" target="_blank"><input type="radio"  name="'.$this->getName().'" id="fontcolor_'.$id.'" class="'.$this->getAttribute('class').'" '.$isChecked.' value=\''.$style['value'].'\' '.$onclick.'  style="float:left;margin-top:30px;margin-left:30px">       <label ><img height="72" src="/uploads/'.$style['src'].'" width="96" onclick="document.getElementById(\'fontcolor_'.$id.'\').checked=\'checked\';'.$this->getAttribute('onClick').'" onmouseover="this.style.cursor=\'pointer\'" ></label><span style="margin-left:45px" id="'.$this->getName().'_'.$style['value'].'">'.$style['cname'].'</span></a>';
            $j++;
        }
        return $html;
    }


}


<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午4:07
 */

namespace Catchtech\Extensions\Factory;



class FormFactory {
    //单例模式页面存储
    public static $_instance=array();


    public function __construct(){

    }

    /**
     * @param $table
     * 验证类型存储格式array('type'=>"类型名称","rule"=>array();;
     * bug 欠缺当放生改变时怎么更新静态变量！ 两个方案一个发生改变时删除静态变量  2 在存一个当时生成值的静态变量
     */
    public static function getInstance($table,$fields,$find=null,$isSearch="0")
    {
        if(!self::$_instance[$table])
        {
            $form = new \Phalcon\Forms\Form();

            $model = new \Catchtech\Extensions\Model\VirtualModel();
            foreach($fields as  $field)
            {
                if($field->reftable && $field->reffield)
                {
                    $refData = $model->refData($field);
                    $ref = array();
                    $reffield = $field->reffield;
                    if($isSearch=='1')
                    {
                        $ref['noValue'] = "请选择";
                    }
                    foreach($refData as $v)
                    {
                        $ref[$v['code']] = $v[$reffield];
                    }
                    $fieldObj = new \Catchtech\Extensions\Fields\SelectField($field->ename,$ref);
                    $fieldPrompt = json_decode($field->prompt,true);
                    if($fieldPrompt['type'])
                    {
                        $fieldObj->addValidator(new $fieldPrompt['type']($fieldPrompt['rule']));
                    }
                    $fieldObj->setAttribute("class",'');
                    $fieldObj->setAttribute("value",$find[$field->ename]);
                    if($isSearch=='1'){
                        $fieldObj->setAttribute("id",$field->ename.$isSearch);
                    }
                    $form->add($fieldObj);
                }
                elseif($field->Linkedfield && $isSearch=='1'){
                    $ref['noValue']="请选择";
                    $fieldObj = new \Catchtech\Extensions\Fields\SelectField($field->ename,$ref);
                    $fieldObj->setAttribute("id",$field->ename.$isSearch);
                    $form->add($fieldObj);
                }
                elseif($field->styletype=='\Catchtech\Extensions\Fields\CheckField')
                {
                    foreach(json_decode($field->contents,true) as $k=>$v)
                    {
                        $fieldObj = new \Catchtech\Extensions\Fields\CheckField($field->ename,$v,'');
                        $fieldPrompt = json_decode($field->prompt,true);
                        if($fieldPrompt['type'])
                        {
                            $fieldObj->addValidator(new $fieldPrompt['type']($fieldPrompt['rule']));
                        }
                        $fieldObj->setAttribute("class",'');
                        $fieldObj->setAttribute("value",$k);
                        if($find[$field->ename]==$k){
                            $fieldObj->setAttribute("checked","checked");
                        }
                        $form->add($fieldObj);
                    }
                }
                else
                {
                    if($field->styletype=='\Catchtech\Extensions\Fields\SelectField' )
                    {
                        $select = array();
                        if($isSearch=='1')
                        {
                            $select['noValue']="请选择";
                        }
                        $contents = array_merge($select,json_decode($field->contents,true));

                        $fieldObj = new $field->styletype($field->ename,$contents);

                    }
                    elseif($field->styletype=='\Catchtech\Extensions\Fields\ComboBoxFiled')
                    {
                        $fieldObj = new $field->styletype($field->ename,$field->code,'');

                    }
                    else
                    {
                        $fieldObj = new $field->styletype($field->ename);
                    }

                    $fieldPrompt = json_decode($field->prompt,true);
                    if($fieldPrompt['type'])
                    {
                        $fieldObj->addValidator(new $fieldPrompt['type']($fieldPrompt['rule']));
                    }
                    $fieldObj->setAttribute("class",'');
                    $fieldObj->setAttribute("value",$find[$field->ename]);
                    $form->add($fieldObj);
                }
                if(self::isQuote($table,$field->ename,$isSearch)){
                    $fieldObj->setAttribute("onchange",self::isQuote($table,$field->ename,$isSearch));
                }
            }
            self::$_instance[$table] = $form;
        }
        return self::$_instance[$table];
    }
    //为字段增加样式属性与验证
    public function fieldExtra($formFieldObj,$fieldValue)
    {
        //为字段增加样式
        //$filedObj[$k]->setAttribute("class",'easyui-validatebox validatebox-text');
    }
    //根据字段验证类型来判断如何验证
    public function validation($obj,$value)
    {
        return false;
    }
    public static function isQuote($tables,$field,$issearch='0'){
        $sqlObj = new \Bpai\Models\CoreFields();
        $sqlObj->setWhere(array("core_code"=>$tables,array("Linkedfield","!=","")));
        $result = $sqlObj->listRec();
        if($result){
            foreach($result->toArray() as $key=>$val){
                $linkFiles = json_decode($val['Linkedfield'],true);
                if($field == $linkFiles['thisFiles']){
                    if($issearch == '1'){
                        return "showLinked('".$val['ename'].$issearch."','".$linkFiles['quoteTables']."','".$field.$issearch."','".$field."')";
                    }else{
                        return "showLinked('".$val['ename']."','".$linkFiles['quoteTables']."','".$field."')";
                    }
                }
            }
        }
    }
} 
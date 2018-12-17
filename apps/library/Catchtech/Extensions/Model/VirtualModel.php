<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-9
 * Time: 上午9:45
 */

namespace Catchtech\Extensions\Model;
use Catchtech\Exceptions;

class VirtualModel extends ModelAbstract
{
    use \Catchtech\Extensions\Traits\MagicVirtualModel;

    public $_table;
    public $_whereCode;
    public $_limit;
    public $_searchWhere;
    public $_order;

    /**
     * 添加修改操作
     * @param $data
     * @return mixed
     */
    public function saveRec($data)
    {
       
        $this->isOnly($data);
        if($data['code'])
        {
            if(is_array($data) === TRUE)
            {
                $str = '';
                foreach($data as $k =>$v)
                {
                    $str .= "`{$k}` = '{$v}',";
                }
                $str = substr($str, 0, -1);
                $sql = "update ".$this->_table." set ".$str." where code='".$data['code']."'";
                $connection = $this->getDI();
                $res = $connection['db']->query($sql);
            }
        }
        else
        {
            if(is_array($data)===TRUE)
            {
                $data['code'] = substr(md5(time().rand(10000,50000)),10);
                if(!$data['status']){ 
                    $data['status'] = '0'; 
                }
                $field = "";
                $value = "";

                foreach($data as $k =>$v)
                {
                    $value .= "'{$v}' ,";
                    $field .= "`{$k}` ,";
                }
                $field = substr($field, 0, -1);
                $value = substr($value, 0, -1);
                $sql   = 'insert into '.$this->_table.'('.$field.') values('.$value.')';
                $connection = $this->getDI();
                $res = $connection['db']->query($sql);
            }
        }

        return $res;
    }

    //删除操作
    public function delRec()
    {
        $sql = " DELETE FROM ".$this->_table." where code='".$this->_whereCode."'";
        $connection = $this->getDI();
        $res = $connection['db']->query($sql);
        return $res;
    }
    //删除操作
    public function updelRec()
    {
        $sql = "update ".$this->_table." set status=1 where code='".$this->_whereCode."'";

        $connection = $this->getDI();
        $res = $connection['db']->query($sql);
        return $res;
    }
    //获取引用表数据
    public function refData($field)
    {
        $sql = "select code,".$field->reffield." from ".$field->reftable." where status!=1 ";
        $connection = $this->getDI();
        $res = $connection['db']->query($sql)->fetchAll();
        return $res;
    }
    //渲染添加页面
    public function renderSave()
    {
        $fields =$this->getFields();
        $formObj = new \Catchtech\Extensions\Factory\FormFactory();
        $form = $formObj::getInstance($this->_table,$fields);

        return array('fields'=>$fields,'form'=>$form);

    }
    //渲染步骤的form表单页面({{_SESSION['login']['name']}})
    public function renderSave_flowstep($fields){
        $fields =$this->getListFieldsFlows($fields);
        $formObj = new \Catchtech\Extensions\Factory\FormFactory();
        $form = $formObj::getInstance($this->_table,$fields);
        return array('fields'=>$fields,'form'=>$form);
    }
    //渲染修改步骤的form表单页面({{_SESSION['login']['name']}})
    public function renderEdit_flowstep($fields)
    {
        $fields =$this->getListFieldsFlows($fields);
        $find = $this->findRec();
        $formObj = new \Catchtech\Extensions\Factory\FormFactory();
        $form = $formObj::getInstance($this->_table,$fields,$find);

        return array('fields'=>$fields,'find'=>$find,'form'=>$form);

    }
    //渲染修改页面
    public function renderEdit()
    {
        $fields =$this->getFields();
        $find = $this->findRec();
        $formObj = new \Catchtech\Extensions\Factory\FormFactory();
        $form = $formObj::getInstance($this->_table,$fields,$find);

        return array('fields'=>$fields,'find'=>$find,'form'=>$form);

    }
    //获取表内的所有字段
    public function getFields()
    {
        $tableObj = new \Bpai\Models\Core();
        $tableObj->setWhere(array('code'=>$this->_table));
        $tableinfo = $tableObj->findRec();
        $fieldModel = new \Bpai\Models\CoreFields();
//        if($tableinfo->tabletag == "flows"){
//            $sflows = new \Bpai\Models\WorkSflows();
//            $sflows->setWhere(array("fcode"=>$this->_table));
//            $result = $sflows->findRec();
//            $fields = json_decode($result->contents);
//            $set_inValue = "'";
//            $set_inValue .= implode("','",$fields);
//            $set_inValue .= "'";
//            $fieldModel->setWhere("AND customtables_ename  = '".$this->_table."' AND  islist  = '1' AND  ename  in ($set_inValue)");
//        }
        $fieldModel->setWhere(array('core_code'=>$this->_table));
        $fields =$fieldModel->listRec();
        return $fields;
    }
    //获取列表字段
    public function getListFields()
    {
        $fieldModel = new \Bpai\Models\Core();
        $fieldModel->setWhere(array('core_code'=>$this->_table,'islist'=>'1'));
        $fields =$fieldModel->listRec();
        return $fields;
    }
    //获取流程步骤显示的字段({{_SESSION['login']['name']}})
    public function getListFieldsFlows($fields)
    {
        $fieldModel = new \Bpai\Models\CoreFields();
        //$fieldModel->setWhere(array('customtables_ename'=>$this->_table,'islist'=>'1',));
        $set_inValue = "'";
        if(!empty($fields)){
            $set_inValue .= implode("','",$fields);
        }
        $set_inValue .= "'";
        $fieldModel->setWhere("AND core_code  = '".$this->_table."' AND  islist  = '1' AND  ename  in ($set_inValue)");
        $fields =$fieldModel->listRec();
        return $fields;
    }
    //获取列表数据
    public function listRec()
    {
        $sql="select * from ".$this->_table ." where status!='1' ".$this->_searchWhere." ".$this->_order." ".$this->_limit;

        $connection = $this->getDI();
        $res = $connection['db']->query($sql);
        $res = $res->fetchAll();
        return $res;

    }
    //获取一条数据
    public function findRec()
    {
        $sql="select * from ".$this->_table.' where code="'.$this->_whereCode.'"';
        $connection = $this->getDI();
        $res = $connection['db']->query($sql);
        $res = $res->fetch();
        return $res;
    }
    //获取一条数据
    public function findRec_w()
    {
        $sql="select * from ".$this->_table.' where '.$this->_whereCode.'';
        $connection = $this->getDI();
        $res = $connection['db']->query($sql);
        $res = $res->fetch();
        return $res;
    }
    //验证唯一性
    public function isOnly($data)
    {
        $fieldModel = new \Bpai\Models\CoreFields();
        $fieldModel->setWhere(array('core_code'=>$this->_table,'isonly'=>'1'));
        $fields =$fieldModel->listRec()->toArray();
        if(!empty($fields)){
            $where='';
            foreach($fields as $v)
            {
                $where .= $v['ename']."='".$data[$v['ename']]."' and ";
            }
            $where = substr($where, 0,-4);
            $sql="select * from ".$this->_table.' where '.$where.' and status!=1';
            $connection = $this->getDI();
            $res = $connection['db']->query($sql);
            $res = $res->fetch();
            if($res && $res['code']!=$data['code'])
            {
                throw new \Phalcon\Exception('数据重复，请重试！');
            }
        }
    }

    /**
     * @此函数的作用是查询多少条记录
     * @$vs_row，limit查询多少条
     * @vs_start,limit 从那开始
     * @返回值是LIMIT+多少条到多少条
     */
    public function set_limit($vs_row=0,$vs_start=0)
    {

        if($vs_row && $vs_start){}
        {
            $this->_limit = "LIMIT $vs_start,$vs_row";

        }
    }
    //处理列表数据
    public function processList($field,$value)
    {
        if($field->reftable && $field->reffield)
        {
            $sql = "select ".$field->reffield." from ".$field->reftable." where code='".$value."'";
            $connection = $this->getDI();
            $res = $connection['db']->query($sql);
            $res = $res->fetch();
            $value = $res[$field->reffield];
        }
        elseif($field->Linkedfield){
            $fieldModel = new \Bpai\Models\CoreFields();
            $connection = $this->getDI();
            $links = json_decode($field->Linkedfield,true);

            $fieldModel->setWhere(array('core_code'=>$links['thisTables'],'ename'=>$links['thisFiles']));
            $fields = $fieldModel->findRec();
//            var_dump($result->toArray());exit;
            $issql = "SELECT `".$fields->reffield."` FROM ".$links['quoteTables']." WHERE `code`='".$value."'";
//            echo $issql;exit;
            $res = $connection['db']->query($issql);
            $res = $res->fetch();
//var_dump($res);exit;
//            $sql = "select ".$res['reffield']." from ".$res['reftable']." where code='".$value."'";
//            $result = $connection['db']->query($sql);
//            $result = $result->fetch();
            $value =  $res[$fields->reffield];
        }
        else
        {
            switch ($field->styletype)
            {
                case '\Catchtech\Extensions\Fields\FileField':
                    $value = "<img src='/uploads/".$value."' width='120' height='90'>";
                    break;
                case '\Catchtech\Extensions\Fields\SelectField':
                    $selectArr = json_decode($field->contents,true);
                    $value = $selectArr[$value];
                    break;
            }
        }
        return $value;
    }
    //渲染搜索框
    public function randerSearch($find)
    {
        $fieldModel = new \Bpai\Models\CoreFields();
        if($find['form_code']){
            $workflow_forms = new \Bpai\Models\WorkflowForms();
            $workflow_forms->setWhere(array("code"=>$find['form_code']));
            $r = $workflow_forms->findRec();
            if($r){
                $r = $r->toArray();
                $form_field = json_decode($r['form_field']);
                $form_field = "('".implode("','",$form_field)."')";
                $fieldModel->setWhere("and customtables_ename ='".$this->_table."' AND issearch = '1' AND ename in ".$form_field);
            }
        }else{
            $fieldModel->setWhere(array('customtables_ename'=>$this->_table,issearch=>'1'));
        }
        $fields =$fieldModel->listRec();
//        echo '<pre>';
//        var_dump($fields->toArray());exit;
        $formObj = new \Catchtech\Extensions\Factory\FormFactory();
        $form = $formObj::getInstance($this->_table,$fields,$find,'1');
        return array('fields'=>$fields,'form'=>$form);
    }
    //拼接搜索where
    public function searchWhere($whereArr)
    {
        if(is_array($whereArr) === true)
        {//是数组
            $str='';

            foreach ($whereArr as $k=>$v)
            {

                if($v && $v!='noValue')
                {
                    $fieldModel = new \Bpai\Models\CoreFields();
                    $fieldModel->setWhere(array('core_ename'=>$this->_table,'ename'=>$k,'issearch'=>'1'));
                    $res =$fieldModel->findRec();

                    if($res)
                    {
                        if($res->searchopt=='=')
                        {
                            $str .= '`'.$k.'` = "'.$v.' " and ';
                        }
                        elseif($res->searchopt=='like')
                        {
                            $str .= '`'.$k.'` like "%'.$v.'%" and ';
                        }
                    }else if($k == "status"){
                        $str .= '`'.$k.'` = "'.$v.' " and ';
                    }
                }
            }

            if($str)
            {
                $this->_searchWhere=" and ".substr($str, 0,-4);
            }
        }else{
            $this->_searchWhere=" and ".$whereArr;
        }
    }
    //设置查询排序
    public function order($field,$type="desc"){
        $this->_order = "order by ".$field." ".$type;
    }

}
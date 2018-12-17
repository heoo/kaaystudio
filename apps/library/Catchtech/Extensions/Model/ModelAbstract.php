<?php
/**
 * Created by JetBrains PhpStorm.
 * User: haowei
 * Date: 14-4-30
 * Time: 下午4:56
 * To change this template use File | Settings | File Templates.
 * 数据模型基类
 */

namespace Catchtech\Extensions\Model{

    abstract class ModelAbstract extends \Phalcon\Mvc\Model
    {
        // 排序
        protected $rs_order_by;

        //where 条件
        protected $rs_var_where;

        //查询的总条件
        protected $_condition;

        abstract public function saveRec($data);

        abstract public function delRec();

        abstract public function listRec();

        abstract public function findRec();



    }
}
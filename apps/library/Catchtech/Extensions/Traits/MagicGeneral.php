<?php
/**
 * Created by JetBrains PhpStorm.
 * User: haowei
 * Date: 14-7-10
 * Time: 下午2:39
 * To change this template use File | Settings | File Templates.
 */

namespace Catchtech\Extensions\Traits;


trait MagicGeneral {

    /**
     *
     * 设置类属性的魔术方法
     * @param String $field 属性名
     * @param String $value 属性值
     * @return Void
     * @example:
     * 	$exampleClass = new subClassOfZendExModel();
     * 	$exampleClass->$name = $value;
     */
    public function __set ($field, $value)
    {
        //调用验证数据方法
        $validMethodName = 'valid'.ucfirst($field);
        if (method_exists($this, $validMethodName)){
            $validMethod = new \ReflectionMethod($this, $validMethodName);
            $validMethod->invoke($this, $value);
        }

        //调用设置数据方法
        $setMethodName = 'set'.ucfirst($field);
        if (method_exists($this, $setMethodName)){
            $setMehtod = new \ReflectionMethod($this, $setMethodName);
            $setMehtod->invoke($this, $value);
        }


        /*
         * 调用回调方法,即当指定的属性赋值完毕后，再调用的一个函数
         * 例如:
         *
         * key的模板为:user:{%code%}
         * 那么可以设置code赋值回调函数来生成key
         * 函数命名规则:
         * function callbackCode()
         */

        $cbMethodName = 'callback'.ucfirst($field);
        if (method_exists($this,$cbMethodName)){
            $cbMethod = new \ReflectionMethod($this, $cbMethodName);
            $cbMethod->invokeArgs($this,array($field,$value));
        }

    }

    /**
     *
     * 获取类属性的魔术方法
     * @param String $field 属性名
     * @return Void
     * @example:
     * 	$exampleClass = new subClassOfZendExModel();
     * 	echo $exampleClass->$name;
     */
    public function __get ($field)
    {
        //获取数据方法
        $getMethodName = 'get'.ucfirst($field);
        if (method_exists($this, $getMethodName)){
            $getMehtod = new \ReflectionMethod($this, $getMethodName);
            return $getMehtod->invoke($this,null);
        }
    }


    /**
     *
     * 从数组中导入
     *
     * 注意此方法会执行数据验证步骤
     * 即枚举调用 setter 进行赋值
     *
     * @param Array $data PO值
     * @return Void
     * @example
     * 	$examleClass = new subClassOfZendExModel();
     * 	$examleData = array();
     *  $exampleClass->__fromArray($exampleData);
     * @throws
     * 	Zend_Exception
     */
    public function __fromArray ($data)
    {
        foreach ($data as $field => $value) {
            $this->$field = $value;
        }

    }

    /**
     *
     * 导入到数组中
     * @return Void
     * @example
     * 	$exampleClass = new subClassOfZendExModel();
     * 	$data = $example->__toArray();
     *
     */
    public function __toArray ()
    {

        $refClass = new ReflectionClass($this);

        $rtnArray = array();
        foreach ($refClass->getProperties(ReflectionProperty::IS_PROTECTED) as $property) {
            $key = substr($property->getName(), 1);
            $rtnArray[$key] = $this->$key;
        }
        return $rtnArray;
    }

    /**
     *
     * 用于克隆对象时调用
     * @return Void
     */
    public function __clone(){


    }

    /**
     * 清除数据
     * @return Void
     */
    public function __clear(){
        $refClass = new ReflectionClass($this);
        foreach ($refClass->getProperties() as $property){
            $key = $property->getName();
            $this->$key = null;
        }
    }

}
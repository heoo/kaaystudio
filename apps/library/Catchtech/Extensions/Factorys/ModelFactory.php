<?php
/**
 * Created by JetBrains PhpStorm.
 * User: haowei
 * Date: 14-7-10
 * Time: 下午3:03
 * To change this template use File | Settings | File Templates.
 */

namespace Catchtech\Extensions\Factory;
use Catchtech\Extensions\Model;

class ModelFactory {

    //保存类实例的静态成员变量
    /**
     * @var \Catchtech\Extensions\Model\VirtualModel
     */
    private static $_instance;

    /**
     * @var \Catchtech\Extensions\Model\ModelAbstract
     */
    protected $_tableModel;

    /**
     * @var \Catchtech\Extensions\Model\ModelAbstract
     */
    protected $_fieldModel;

    //private标记的构造方法
    public function __construct(){


//        $this->_targetModel = new \Phalcon\MVC\Model('redff');
//
//        echo '禁止直接实例化！';
//
//        $fields = array();
//
//        foreach ($fields as $field){
//            $this->$field= null;
//        }
    }

    //创建__clone方法防止对象被复制克隆
    public function __clone(){
        trigger_error('此对象不能被cope', E_USER_ERROR);
    }

    //单例方法,用于访问实例的公共的静态方法
    public static function getInstance(){

        $fields = array('code'=>'tests','role_code'=>'123');
        self::$_instance = new Model\VirtualModel('acls');

        foreach ($fields as $field=>$value){
            self::$_instance->$field = $value;
        }

        return self::$_instance;
    }


}
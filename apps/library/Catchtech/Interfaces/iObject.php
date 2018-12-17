<?php


/**
 * 项目中所有自定义类的基类
 * 提供属性赋值、取值的魔术方法
 * @author haowei
 * @ver 1.0
 *
 */
namespace Catchtech\Interfaces {

    interface iObject {

        public function __set($field,$value);
        public function __get($field);
        public function __fromArray($data);
        public function __toArray();
        public function __clone();
        public function __clear();

    }



}




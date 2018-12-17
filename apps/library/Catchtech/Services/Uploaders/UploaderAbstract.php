<?php


namespace Catchtech\Services\Uploaders {

    /**
     * 上传抽象类
     * 为扩展上传类提供标准接口
     * @package ZendEx
     * @subpackage Uploaders
     * @abstract
     *
     * @author machao
     *
     */

    abstract class UploaderAbstract extends \Phalcon\DI\Injectable implements \Catchtech\Interfaces\ObjectInterface
    {


        use \Catchtech\Interfaces\iObjectImpelemetion;

        /**
         * 命名类
         * @var \Catchtech\Services\Uploader\Namepolicys\NamepolicyAbstract
         */
        protected $_namepolicy;
        /**
         * @todo 获取文件后缀名
         * @param string $file_name
         * @return string
         */
        protected function _extend($file_name)
        {
            $retval="";
            $pt=strrpos($file_name, ".");
            if ($pt) $retval=substr($file_name, $pt+1, strlen($file_name) - $pt);
            return ($retval);
        }

        /**
         *
         * @param \Catchtech\Services\Uploader\Namepolicys\NamepolicyAbstract $namepolicy
         */

        public function __construct(\Catchtech\Services\Uploader\Namepolicys\NamepolicyAbstract $namepolicy){
            $this->_namepolicy = $namepolicy;
        }

        /**
         * 文件上传
         *
         * @param string $sourceFile
         * @return string
         */

        abstract public function upload($sourceFile,$namepolicyOpt);

        /**
         * @return the $_namepolicy
         */
        public function getNamepolicy() {
            return $this->_namepolicy;
        }

        /**
         * @param ZendEx_Uploader_Namepolicy_Abstract $_namepolicy
         */
        public function setNamepolicy($_namepolicy) {
            $this->_namepolicy = $_namepolicy;
        }

    }
}
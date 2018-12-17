<?php

namespace Catchtech\Services\Uploaders {

    use \Catchtech\Services\Uploaders\Exception as Exception;

    class Local extends \Catchtech\Services\Uploaders\UploaderAbstract {

        /**
         * 文件存储物理路径 末尾带结束符
         * @var string
         */
        protected $_basePath = "/";
        /**
         *
         * 文件服务器域名
         * @var unknown_type
         */
        protected $_baseUrl = "";



        /**
         *
         * 根据路径建立目录
         * @param String $dirName
         * @param int $mode
         * @throws ZendEx_File_Hash_Exception
         */
        protected function _createDir( $dirName, $mode = 0777 )
        {
            if ( file_exists($dirName) ) {
                return true;
            }
            $dirName = str_replace("\\", "/", $dirName);
            if (substr($dirName, strlen($dirName)-1 ) == "/" ) {
                $dirName = substr($dirName, 0,strlen($dirName)-1);
            }
            // for example, we will create dir "/a/b/c"
            // $firstPart = "/a/b"
            $firstPart = substr( $dirName, 0, strrpos($dirName, "/" ) );

            if ( file_exists($firstPart) ){
                if ( !mkdir($dirName,$mode) ) {
                    throw new Exception("【文件系统】创建目录失败",'FIL0001');
                }
                chmod( $dirName, $mode );
            } else {
                $this->_createDir($firstPart,$mode);
                if ( !mkdir($dirName,$mode) ) {
                    throw new Exception("【文件系统】创建目录失败",'FIL0001');
                }

                chmod( $dirName, $mode );
            }

            return true;
        }

        /* (non-PHPdoc)
         * @see ZendEx_Uploader_Abstract::__construct()
         */
        public function __construct(\Catchtech\Services\Uploaders\Namepolicys\NamepolicyAbstract $namepolicy, $option) {
            // TODO Auto-generated method stub
            /* if(isset($option['httpAdapter'])){
                $this->setHttpAdapter($option['httpAdapter']);
            } */
            parent::__construct($namepolicy,$option);
        }

        /* (non-PHPdoc)
         * @see ZendEx_Uploader_Abstract::upload()
         */
        public function upload($sourceFile, $namepolicyOpt) {
            // TODO Auto-generated method stub
            if(!is_file($sourceFile)){
                throw new Exception("源文件不存在。");
            }
            if(!is_readable($sourceFile)){
                throw new Exception("源文件不能被移动");
            }
            if(!($this->_namepolicy instanceof \Catchtech\Services\Uploaders\Namepolicys\NamepolicyAbstract)){
                throw new Exception("无效的命名策略对象");
            }
            $dFile      = $this->_namepolicy->getName($namepolicyOpt);
            $targetFile = $this->getBasePath().$dFile;
            if($this->_createDir(dirname($targetFile))){
                if(rename($sourceFile,$targetFile)){
                    return $this->_baseUrl.$dFile;
                }
            }
            throw new Exception("文件上传失败！");
        }

        /**
         * @return the $_basePath
         */
        public function getBasePath() {
            return $this->_basePath;
        }

        /**
         * @return the $_baseUrl
         */
        public function getBaseUrl() {
            return $this->_baseUrl;
        }


        /**
         * @param string $_basePath
         */
        public function setBasePath($_basePath) {
            $this->_basePath = $_basePath;
        }

        /**
         * @param unknown_type $_baseUrl
         */
        public function setBaseUrl($_baseUrl) {
            $this->_baseUrl = $_baseUrl;
        }


    }
}


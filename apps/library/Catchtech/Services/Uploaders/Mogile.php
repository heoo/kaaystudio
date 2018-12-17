<?php
/**
 * MogileFS上传
 * @author Haowei
 * @version 1.0
 */
namespace Catchtech\Services\Uploaders {

    use \Catchtech\Services\Uploaders\Exception as Exception;

    class Mogile extends \Catchtech\Services\Uploaders\UploaderAbstract {

        /**
         * MogileFS tracker地址
         * @var string
         */
        protected $_host;

        /**
         * MogileFS 端口
         * @var string
         */
        protected $_port;

        /**
         * MogileFS 域名
         * @var string
         */
        protected $_domain;


        /**
         * MogileFS 类名
         * @var string
         */
        protected $_class;
        /**
         *
         * 文件访问路径
         * @var string
         */
        protected $_baseUrl;

        /**
         * 设置mogile信息
         *
         * @param array $option
         */
        protected function _setOption($option){

            //$reflectionClass = new ReflectionClass($this);
            if(is_array($option) && !empty($option)){
                foreach($option as $pro=>$val){
                    $methodName = "set".ucfirst($pro);
                    if(method_exists($this,$methodName)){
                        $this->$methodName($val);
                    }
                }
            }
        }
        /* (non-PHPdoc)
         * @see ZendEx_Uploader_Abstract::__construct()
         * @todo 为uploader设置namepolicy
         *       初始化上传控件的配置信息
         * @author Haowei
         */
        public function __construct(\Catchtech\Services\Uploaders\Namepolicys\NamepolicyAbstract $namepolicy, $option = null) {
            // TODO Auto-generated method stub
            $this->_setOption($option);
            parent::__construct($namepolicy);
        }

        /**
         * (non-PHPdoc)
         * @see ZendEx_Uploader_Abstract::upload()
         * @todo 上传
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
            $dFile       = $this->_namePolicy->getName($namepolicyOpt);
            $suffix      = $this->_extend($sourceFile);
            $filePath    = $dFile.$suffix;
            $mgfsClient  = new MogileFs();
            try{
                $mgfsClient->connect($this->getHost(),$this->getPort(),$this->getDomain());
            }catch (Exception $err){
                throw new Exception("无法连接到文件服务器");
            }
            $mgfsClient->put($sourceFile,$filePath,$this->getClass());
            return $this->getBaseUrl().$filePath;
        }

        /**
         * @return the $_host
         */
        public function getHost() {
            return $this->_host;
        }

        /**
         * @return the $_port
         */
        public function getPort() {
            return $this->_port;
        }

        /**
         * @return the $_domain
         */
        public function getDomain() {
            return $this->_domain;
        }

        /**
         * @return the $_class
         */
        public function getClass() {
            return $this->_class;
        }

        /**
         * @return the $_baseUrl
         */
        public function getBaseUrl() {
            return $this->_baseUrl;
        }

        /**
         * @param string $_host
         */
        public function setHost($_host) {
            $this->_host = $_host;
        }

        /**
         * @param string $_port
         */
        public function setPort($_port) {
            $this->_port = $_port;
        }

        /**
         * @param string $_domain
         */
        public function setDomain($_domain) {
            $this->_domain = $_domain;
        }

        /**
         * @param string $_class
         */
        public function setClass($_class) {
            $this->_class = $_class;
        }

        /**
         * @param unknown_type $_baseUrl
         */
        public function setBaseUrl($_baseUrl) {
            $this->_baseUrl = $_baseUrl;
        }

    }
}

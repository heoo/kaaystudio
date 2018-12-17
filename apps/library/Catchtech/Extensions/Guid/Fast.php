<?php
/**
 * ZendEx_Guid_Fast 
 * 
 * 为模型生成 64 位整数或混淆字符串的不重复 ID
 * 使用 64bit 整数存储主键，主键由 fast_uuid 方法在创建记录时调用生成。
 * 参数 suffix_len指定 生成的 ID 值附加多少位随机数，默认值为 3。
 * 即便不附加随机数也不会生成重复 ID，但附加的随机数可以让 ID 更难被猜测。 
 * @author haowei
 *
 */

namespace Catchtech\Extensions\Guid{

    class Fast extends \Catchtech\Extensions\Guid\GuidAbstract {

        /**
         * 后缀随机位数
         * @var int
         */
        protected $_suffixLen;


        /**
         * 构造函数
         * @param int $suffixLen
         *
         */
        function __construct($suffixLen = 0){
            $this->_suffixLen = $suffixLen;

        }

        /**
         * 生成Code
         * @return string
         */
        public function genIdentity() {
            // TODO: Auto-generated method stub
            $being_timestamp = time();

            $time = explode(' ', microtime());
            $guid = ($time[1] - $being_timestamp) . sprintf('%06u', substr($time[0], 2, 6));
            if ($this->_suffixLen > 0)
            {
                $guid .= substr(sprintf('%010u', mt_rand()), 0, $this->_suffixLen);
            }
            return $guid;
        }

    }
}


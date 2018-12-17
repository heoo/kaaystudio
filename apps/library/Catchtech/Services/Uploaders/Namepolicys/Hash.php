<?php

namespace Catchtech\Services\Uploaders\Namepolicys{

    class Hash extends \Catchtech\Services\Uploaders\Namepolicys\NamepolicyAbstract{


        /*
         *
         * @params $option array('seed')
         */
        public function getName(array $option) {
            // TODO Auto-generated method stub
            if(isset($option['seed'])){
                $hash = md5($option['seed']);
                $hash = substr($hash,0,2) ."/".substr($hash,2,3) ."/".substr($hash,5);
                return $hash;
            }
        }
    }
}

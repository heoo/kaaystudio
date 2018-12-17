<?php

namespace Catchtech\Exceptions {

    class Exception extends \Phalcon\Exception {

        function __construct($message){
            $err = explode(":",$message);
            parent::__construct($err[0],$err[1]);
        }

    }
}


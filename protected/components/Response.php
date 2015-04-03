<?php

class Response extends CComponent
{
    protected $_code;
    protected $_message;

    public function __construct(){
        $this->_code = 400;
        $this->_message = '';
    }

    public function getCode() {
        return $this->_code;
    }

    public function getMessage() {
        return $this->_message;
    }

    public function setCode($value) {
        $this->_code = $value;
    }

    public function setMessage($value) {
        $this->_message = $value;
    }
} 
<?php


class Request
{
    var $url;
    var $method;
    var $data;
    function __construct()
    {
        $this->method=$_SERVER['REQUEST_METHOD'];
        $this->url=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
        $this->data=json_decode(file_get_contents("php://input"));
    }

}
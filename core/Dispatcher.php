<?php


class Dispatcher
{
    var $request;
    function __construct()
    {
        $this->request=new Request();
        Router::parse($this->request->url,$this->request);


        $controller=$this->loadController();
        call_user_func_array(array($controller,strtolower($this->request->method)), $this->request->params);


    }

    function loadController(){
        $name=ucfirst($this->request->controller).'Controller';
        $file= ROOT.DS.'controller'.DS.$name.'.php';
        if (file_exists($file)) {
            //require "must_have.php";
            require $file;
        }
        else {
            //return $this->error('not found : '.$this->request->controller);
            return 'error';
        }
        return new $name($this->request);
    }
}
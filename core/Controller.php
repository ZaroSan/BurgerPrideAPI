<?php
header("Access-Control-Allow-Origin:*");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST;DELETE;PUT;GET");

class Controller
{
    var $method;
    var $id;
    var $controller;
    var $data;
    function __construct($request)
    {
        $this->data=$request->data;
        $this->method=$request->method;
        $this->id=isset($request->params[0])?$request->params[0]:null;
        $this->controller=ucfirst($request->controller);
    }
    public function loadModel($name){
        $file=ROOT.DS.'model'.DS.$name.'.php';
        require_once($file);
        if(!isset($this->$name)){
            $this->$name = new $name();
            return $this->$name;
        }

    }
    function get(){
        if(!isset($this->id))
            $d['list']=$this->loadModel($this->controller)->readAll();
        else
            $d[$this->controller]=$this->loadModel($this->controller)->readFirst($this->id);
        print_r(json_encode($d));
    }
    function post(){
        $this->loadModel($this->controller)->save($this->data);
    }
    function put(){
        $this->loadModel($this->controller)->save($this->data);
    }
    function delete(){
        if(isset($this->id))
            $this->loadModel($this->controller)->delete($this->id);
    }
}
<?php
    header("Access-Control-Allow-Origin:*");
    header("Content-type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods:POST;DELETE;PUT;GET");

    class Controller
    {
        public $method;
        public $id;
        public $controller;
        public $data;
        public $params = array();

        function __construct($request)
        {
            $this->data = $request->data;
            $this->method = $request->method;

            if (isset($request->params[0]))
            {
                $this->id=array_shift($request->params);
                if(isset($request->params[0]))
                {
                    $this->params = $request->params;
                }
            }
            else
            {
                $this->id = null;
            }
            $this->controller = ucfirst($request->controller);
        }

        public function loadModel($name)
        {
            $file=ROOT.DS.'model'.DS.$name.'.php';
            require_once($file);
            if(!isset($this->$name)){
                $this->$name = new $name();
                return $this->$name;
            }
        }

        function get()
        {
            if(!isset($this->id))
                $d[$this->controller.'s']=$this->loadModel($this->controller)->readAll();
            else
                $d[$this->controller]=$this->loadModel($this->controller)->readFirst($this->id);
            print_r(json_encode($d));
        }

        function post()
        {
            $d['result']=$this->loadModel($this->controller)->save($this->data);
            print_r(json_encode($d));
        }

        function put()
        {
            $this->loadModel($this->controller)->save($this->data);
        }

        function delete()
        {
            if(isset($this->id))
                $this->loadModel($this->controller)->delete($this->id);
        }
    }
<?php
    class Request
    {
        public $url;
        public $method;
        public $data;

        function __construct()
        {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->url = $_SERVER['QUERY_STRING'] ?? $_SERVER['PATH_INFO'] ?? '/';
            $this->data = json_decode(file_get_contents('php://input'));
        }

    }
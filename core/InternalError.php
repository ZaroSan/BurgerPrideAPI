<?php
    class InternalError {
        static $code = 500;
        static $message = "Internal Error";
        static $data = array();

        static function display()
        {
            header('HTTP/1.0 '.self::$code.' '.self::$message);
            echo json_encode(array(
                'code' => self::$code,
                'message' => self::$message,
                'data' => self::$data
            ));
        }

        static function get()
        {
            InternalError::display();
        }

        static function post()
        {
            InternalError::display();
        }

        static function put()
        {
            InternalError::display();
        }

        static function patch()
        {
            InternalError::display();
        }

        static function delete()
        {
            InternalError::display();
        }
    }
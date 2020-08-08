<?php
    class InternalError {
        static $code = 500;
        static $message = "Internal Error";

        static function display()
        {
            header('HTTP/1.0 '.InternalError::$code.' '.InternalError::$code);
            echo json_encode(array(
                'code' => InternalError::$code,
                'message' => InternalError::$message
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
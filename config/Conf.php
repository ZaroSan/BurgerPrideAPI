<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    class Conf {
        static $debug = 1;

        static $jwt = array(
            'secret' => 'your-256-bit-secret',
            'algo' => 'HS256'
        );

        static $databases = array(
            'local' => array(
            'host' => 'localhost',
            'database' => 'burgerpride',
            'login' => 'root',
            'password' => 'password'
            )
        );
        static $confDB = 'local';

        static $recaptcha = array(
            'mobile' => array(
                'secret' => 'secret-api-key'
            )
        );
        static $defaultRecaptcha = 'mobile';
    }

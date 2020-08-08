<?php
    class Conf {
        static $debug = 1;

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

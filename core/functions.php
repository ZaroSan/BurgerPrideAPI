<?php
    function debug($var) {
        if(Conf::$debug >0){
            $debug = debug_backtrace();

            echo '<p><a href="#"><strong>'.$debug[0]['file'].'</strong> l.'.$debug[0]['line'].'</a></p>';
            echo '<ol>';
            foreach ($debug as $key => $value) {
                if($key >0){
                    echo "<li><strong>".$value['file']."</strong> l.".$value['line']."</li>";
                }
            }
            echo "</ol>";
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }
        
    }

    function check_recaptcha($response, $secret = null) {
        if ($secret == null) {
            $secret = Conf::$recaptcha[Conf::$defaultRecaptcha]['secret'];
        }

        $parameters = array(
            'http' => array(
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array(
                    'secret'   => $secret,
                    'response' => $response,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ))
            )
        );
        $context  = stream_context_create($parameters);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        if ($response == false) {
            return false;
        }
        $r = json_decode($response, true);

        return $r['success'];
    }

    function generate_salt($length = 8) {
        if (!is_numeric($length)) {
            $length = 8;
        }
        $dico = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_=:';
        $str = "";
        for ($i=0; $i < $length; $i++) {
            $str.=$dico[rand(0, strlen($dico)-1)];
        }
        return $str;
    }

    function is_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
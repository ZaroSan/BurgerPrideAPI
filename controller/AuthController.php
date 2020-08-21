<?php
    use Ahc\Jwt\JWT;
    class AuthController extends Controller
    {
        private $db;
        private $jwt;

        public function __construct($request)
        {
            parent::__construct($request);
            // hot fix
            $this->db = (new Model)->db;
            $this->jwt = new JWT(Conf::$jwt['secret'], Conf::$jwt['algo'], 24*3600); // 24h
        }

        function post()
        {
            // hot fix
            $pdo = $this->db;
            $jwt = $this->jwt;
            $req = $pdo->prepare('SELECT id, salt, pass_hash FROM clients WHERE email=:email');
            $req->bindParam(':email', $this->data->email, PDO::PARAM_STR, 256);
            $req->execute();
            if ($res = $req->fetch(PDO::FETCH_ASSOC)) {
                if (md5($res['salt']. $this->data->password) === $res['pass_hash']) {
                    $token = $jwt->encode(['id' => $res['id'], 'scopes' => ['user']]);
                    $json = json_encode(array(
                        'id' => intval($res['id']),
                        'token' => $token
                    ));
                    echo $json;
                    return;
                }
            }
            
            InternalError::$code = 403;
            InternalError::$message = 'Forbidden';
            InternalError::display();
        }

        function get()
        {
            InternalError::$code = 404;
            InternalError::$message = "Not Found";
            InternalError::display();
        }

        function delete()
        {
            # Not implemented...
            InternalError::display();
        }

        function put()
        {
            # Not implemented...
            InternalError::display();
        }
    }
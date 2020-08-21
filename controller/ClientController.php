<?php
    use Ahc\Jwt\JWT;
    class ClientController extends Controller
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
        public function post()
        {
            $requiredFields = array(
                'firstname', 'lastname', 'email', 'password', 'passwordConfirm',
                'street', 'number', 'city', 'zipCode', 'telephone', 'recaptcha'
            );
            $is_fill = array_map(
                function ($field)
                {
                    return isset($this->data->$field);
                },
                $requiredFields
            );

            if (!in_array(false, $is_fill))
            {
                $errors = array();
                $fields = $this->data;

                if (!check_recaptcha($fields->recaptcha))
                {
                    array_push($errors, "Recaptcha incorrecte");
                }

                if (!is_email($fields->email))
                {
                    array_push($errors, "Syntaxe de l'email incorrecte");
                }

                if ($fields->password != $fields->passwordConfirm) {
                    array_push($errors, "Les mots de passe ne correspondent pas");
                }

                if (count($errors) == 0)
                {
                    $salt = generate_salt();
                    $hash = md5($salt.$fields->password);
                    $this->data = array(
                        'firstname' => $fields->firstname,
                        'lastname' => $fields->lastname,
                        'email' => $fields->email,
                        'salt' => $salt,
                        'pass_hash' => $hash,
                        'street' => $fields->street,
                        'number' => $fields->number,
                        'city' => $fields->city,
                        'zip_code' => $fields->zipCode,
                        'telephone' => $fields->telephone
                    );
                    parent::post();
                }
                else
                {
                    InternalError::$code = 400;
                    InternalError::$message = "Bad Request";
                    InternalError::$data = $errors;
                    InternalError::display();
                }
            }
            else
            {
                InternalError::$code = 400;
                InternalError::$message = "Bad Request";
                InternalError::display();
            }
        }

        function put()
        {
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $errors = array();
                $fields = $this->data;

                if (!is_email($fields->email))
                {
                    array_push($errors, "Syntaxe de l'email incorrecte");
                }

                if ($fields->password != $fields->passwordConfirm) {
                    array_push($errors, "Les mots de passe ne correspondent pas");
                }

                if (count($errors) == 0)
                {
                    try {
                        $data = $this->jwt->decode($_SERVER['HTTP_AUTHORIZATION']);
                        $req = $this->db->prepare('SELECT id, salt, pass_hash FROM clients WHERE id=:id');
                        $req->bindParam(':id', $data['id'], PDO::PARAM_INT);
                        $req->execute();
                        if ($res = $req->fetch(PDO::FETCH_ASSOC)) {
                            $pass_hash = strlen($fields->password) > 0 ? md5($res['salt'].$fields->password) : $res['pass_hash'];
                            $req2 = $this->db->prepare("UPDATE clients SET pass_hash=:pass, street=:street, `number`=:number, city=:city, zip_code=:zipCode, telephone=:phone WHERE id=:id");
                            $req2->bindParam(':pass', $pass_hash);
                            $req2->bindParam(':street', $fields->street);
                            $req2->bindParam(':number', $fields->number);
                            $req2->bindParam(':city', $fields->city);
                            $req2->bindParam(':zipCode', $fields->zipCode);
                            $req2->bindParam(':phone', $fields->telephone);
                            $req2->bindParam(':id', $data['id']);
                            echo json_encode(array(
                                'success' => $req2->execute()
                            ));
                            return;
                        } else {
                            InternalError::display();
                            return;
                        }
                    } catch (Exception $e) { }
                } else {
                    InternalError::$code = 400;
                    InternalError::$message = "Bad Request";
                    InternalError::$data = $errors;
                    InternalError::display();
                }
            }
            InternalError::$code = 403;
            InternalError::$message = 'Forbidden';
            InternalError::display();
        }

        function delete()
        {
            # Not implemented...
            InternalError::display();
        }

        function get()
        {
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                try {
                    $data = $this->jwt->decode($_SERVER['HTTP_AUTHORIZATION']);
                    $req = $this->db->prepare('SELECT *, zip_code as zipCode FROM clients WHERE id=:id');
                    $req->bindParam(':id', $data['id'], PDO::PARAM_INT);
                    $req->execute();
                    if ($res = $req->fetch(PDO::FETCH_ASSOC)) {
                        unset($res['pass_hash'], $res['salt'], $res['zip_code']);
                        echo json_encode($res);
                        return;
                    }
                    return;
                } catch (Exception $e) { }
            }
            InternalError::$code = 403;
            InternalError::$message = 'Forbidden';
            InternalError::display();
        }
    }
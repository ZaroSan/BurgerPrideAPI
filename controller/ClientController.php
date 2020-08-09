<?php
    class ClientController extends Controller
    {
        function post()
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
    }
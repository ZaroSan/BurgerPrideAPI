<?php
    use Ahc\Jwt\JWT;
    class BurgerController extends Controller
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
            if (isset($_SERVER['HTTP_AUTHORIZATION']))
            {
                try
                {
                    $data = $this->jwt->decode($_SERVER['HTTP_AUTHORIZATION']);
                    $req = $this->db->prepare("INSERT INTO sandwiches (id_bread, id_client) VALUES (:bread, :client)");
                    $req->bindParam(':bread', $this->data->bread->id);
                    $req->bindParam(':client', $data['id']);
                    $req->execute();
                    $sandwich = $this->db->lastInsertId();
                    foreach ($this->data->ingredients as $ing) {
                        $req2 = $this->db->prepare("INSERT INTO ingredients_lists (id_sandwich, id_ingredient) VALUES (:sw, :ing)");
                        $req2->bindParam(':sw', $sandwich);
                        $req2->bindParam(':ing', $ing->id);
                        $req2->execute();
                    }
                    echo json_encode(array(
                        'id' => $sandwich
                    ));
                    return;
                }
                catch (Exception $e) { }
            }
            InternalError::$code = 403;
            InternalError::$message = 'Forbidden';
            InternalError::display();
        }

        function get()
        {
            if (isset($_SERVER['HTTP_AUTHORIZATION']))
            {
                try
                {
                    $sql = <<<SQL
                    SELECT
                        s.id,
                        b.id as bread_id,
                        b.name as bread_name,
                        b.kcal as bread_kcal,
                        b.bio as bread_bio,
                        b.price as bread_price,
                        i.id as ingredient_id,
                        i.name as ingredient_name,
                        i.kcal as ingredient_kcal,
                        i.bio as ingredient_bio,
                        i.price as ingredient_price
                    FROM sandwiches s
                    JOIN breads b on (s.id_bread = b.id)
                    JOIN ingredients_lists l on (s.id = l.id_sandwich)
                    JOIN ingredients i on (i.id = l.id_ingredient)
                    WHERE s.id_client = :id
SQL;
                    $data = $this->jwt->decode($_SERVER['HTTP_AUTHORIZATION']);
                    $req = $this->db->prepare($sql);
                    $req->bindParam(':id', $data['id']);
                    $req->execute();
                    $burgers = array();
                    while ($res = $req->fetch(PDO::FETCH_ASSOC)) {
                        if (!isset($burgers[$res['id']])) {
                            $burgers[$res['id']] = array(
                                'id' => $res['id'],
                                'bread' => array(
                                    'id' => $res['bread_id'],
                                    'name' => $res['bread_name'],
                                    'kcal' => $res['bread_kcal'],
                                    'bio' => !!$res['bread_bio'],
                                    'price' => $res['bread_price']
                                ),
                                'ingredients' => array()
                            );
                        }
                        array_push($burgers[$res['id']]['ingredients'], array(
                            'id' => $res['ingredient_id'],
                            'name' => $res['ingredient_name'],
                            'kcal' => $res['ingredient_kcal'],
                            'bio' => !!$res['ingredient_bio'],
                            'price' => $res['ingredient_price']
                        ));
                    }
                    usort($burgers, function ($a, $b)
                    {
                        if (!isset($a['id'], $b['id'])) {
                            return 1;
                        }
                        if ($a['id'] == $b['id']) {
                            return 0;
                        }
                        return $a['id'] > $b['id'] ? -1 : 1;
                    });
                    echo json_encode($burgers);
                    return;
                }
                catch (Exception $e) { }
            }
            InternalError::$code = 403;
            InternalError::$message = 'Forbidden';
            InternalError::display();
        }
    }
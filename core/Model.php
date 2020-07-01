<?php class Model{
    public $conf='local';
    static $connections = array();
    public $table= false;
    public $db;
    public $primaryKey ='id';
    public $id;

    function __construct(){
        if($this->table === false){
            $this->table = strtolower(get_class($this)).'s';
        }
        $conf=Conf::$databases[$this->conf];
        if(isset(Model::$connections[$this->conf])){
            $this->db=Model::$connections[$this->conf];
            return true;
        }
        try{
            $pdo = new PDO(
                'mysql:host='.$conf['host'].';dbname='.$conf['database'].';',
                $conf['login'],
                $conf['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => ' SET NAMES utf8')
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            Model::$connections[$this->conf] = $pdo;
            $this->db=$pdo;
        }
        catch(PDOException $e){
            if(Conf::$debug >= 1){
                die($e->getMessage());
            }
            else{
                die('Impossible de se connecter à la base de donnée');
            }
        }

    }

    function readAll(){
        $sql='SELECT *  FROM '.$this->table.' as '.get_class($this);
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);
    }
    function readFirst($id){
        $sql="SELECT * FROM {$this->table} WHERE {$this->primaryKey} = {$id}";
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);

    }
    public function delete($id){
        $sql="DELETE FROM {$this->table} WHERE {$this->primaryKey} = {$id}";
        $this->db->query($sql);
    }


}

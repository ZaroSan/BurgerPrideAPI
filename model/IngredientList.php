<?php


class IngredientList extends Model
{
    public $table ='ingredients_lists';
    public $idIngredient='id_ingredient';
    public $idSandwich='id_sandwich';

    public function deleted($idIngredient,$idSandwich){
        $sql="DELETE FROM {$this->table} WHERE {$this->idSandwich} = {$idSandwich} AND {$this->idIngredient} = {$idIngredient}";
        $this->db->query($sql);
    }
    public function deleteIngredient($id){
        $sql="DELETE FROM {$this->table} WHERE {$this->idIngredient} = {$id}";
        $this->db->query($sql);
    }
    public function deleteSandwich($id){
        $sql="DELETE FROM {$this->table} WHERE {$this->idSandwich} = {$id} ";
        $this->db->query($sql);
    }
    public function readIngredient($id){
        $sql="SELECT * FROM {$this->table} WHERE {$this->idIngredient} = {$id} ";
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);
    }
    public function readSandwich($id){
        $sql="SELECT * FROM {$this->table} WHERE {$this->idSandwich} = {$id} ";
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);
    }
    public function read($idIngredient,$idSandwich){
        $sql="SELECT * FROM {$this->table} WHERE {$this->idSandwich} = {$idSandwich} AND {$this->idIngredient} = {$idIngredient}";
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);
    }
}
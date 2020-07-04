<?php


class IngredientAllergic extends Model
{
    public $table ='ingredients_allergics';
    public $idIngredient='id_ingredient';
    public $idAllergenic='id_allergenic';

    public function deleted($idIngredient,$idAllergenic){
        $sql="DELETE FROM {$this->table} WHERE {$this->idAllergenic} = {$idAllergenic} AND {$this->idIngredient} = {$idIngredient}";
        $this->db->query($sql);
    }
    public function deleteIngredient($id){
        $sql="DELETE FROM {$this->table} WHERE {$this->idIngredient} = {$id}";
        $this->db->query($sql);
    }
    public function deleteAllergenic($id){
        $sql="DELETE FROM {$this->table} WHERE {$this->idAllergenic} = {$id} ";
        $this->db->query($sql);
    }
    public function readIngredient($id){
        $sql="SELECT * FROM {$this->table} WHERE {$this->idIngredient} = {$id} ";
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);
    }
    public function readAllergenic($id){
        $sql="SELECT * FROM {$this->table} WHERE {$this->idAllergenic} = {$id} ";
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);
    }
    public function read($idIngredient,$idAllergenic){
        $sql="SELECT * FROM {$this->table} WHERE {$this->idAllergenic} = {$idAllergenic} AND {$this->idIngredient} = {$idIngredient}";
        $pre=$this->db->prepare($sql);
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_OBJ);
    }
}
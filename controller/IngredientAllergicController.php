<?php


class IngredientAllergicController extends Controller
{

    private $ingredient;
    private $allergenic;

    function __construct($request)
    {
        $this->data=$request->data;
        $this->method=$request->method;

        $this->controller=ucfirst($request->controller);
        $this->id=null;
        for($i=0;$i<sizeof($request->params);$i++){
            if($request->params[$i]=='ingredient'){
                $this->ingredient=$request->params[$i+1];
            }
            elseif ($request->params[$i]=='allergenic'){
                $this->allergenic=$request->params[$i+1];
            }
        }
    }
    public function delete()
    {
        $this->loadModel('IngredientAllergic');
        if(isset($this->allergenic) && isset($this->ingredient)){
            $this->IngredientAllergic->deleted($this->ingredient,$this->allergenic);
        }
        elseif (!isset($this->allergenic) && isset($this->ingredient)){
            $this->IngredientAllergic->deleteIngredient($this->ingredient);
        }
        elseif (isset($this->allergenic) && !isset($this->ingredient)){
            $this->IngredientAllergic->deleteAlergenic($this->allergenic);
        }
    }
    public function put()
    {

    }
    public function get()
    {
        $d['IngredientAllergics']=null;
        $this->loadModel('IngredientAllergic');
        if(isset($this->allergenic) && isset($this->ingredient)){
            $d['IngredientAllergics']=$this->IngredientAllergic->read($this->ingredient,$this->allergenic);
        }
        elseif (!isset($this->allergenic) && isset($this->ingredient)){
            $d['IngredientAllergics']=$this->IngredientAllergic->readIngredient($this->ingredient);
        }
        elseif (isset($this->allergenic) && !isset($this->ingredient)){
            $d['IngredientAllergics']=$this->IngredientAllergic->readAlergenic($this->allergenic);
        }
        else{
            $d['IngredientAllergics']= $this->IngredientAllergic->readAll();
        }
        print_r(json_encode($d));
    }
}
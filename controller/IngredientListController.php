<?php


class IngredientListController extends Controller
{
    private $ingredient;
    private $sandwich;

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
            elseif ($request->params[$i]=='sandwich'){
                $this->sandwich=$request->params[$i+1];
            }
        }
    }
    public function delete()
    {
        $this->loadModel('IngredientList');
        if(isset($this->sandwich) && isset($this->ingredient)){
            $this->IngredientList->deleted($this->ingredient,$this->sandwich);
        }
        elseif (!isset($this->sandwich) && isset($this->ingredient)){
            $this->IngredientList->deleteIngredient($this->ingredient);
        }
        elseif (isset($this->sandwich) && !isset($this->ingredient)){
            $this->IngredientList->deleteSandwich($this->sandwich);
        }
    }
    public function put()
    {

    }
    public function get()
    {
        $d['IngredientLists']=null;
        $this->loadModel('IngredientList');
        if(isset($this->sandwich) && isset($this->ingredient)){
            $d['IngredientLists']=$this->IngredientList->read($this->ingredient,$this->sandwich);
        }
        elseif (!isset($this->sandwich) && isset($this->ingredient)){
            $d['IngredientLists']=$this->IngredientList->readIngredient($this->ingredient);
        }
        elseif (isset($this->sandwich) && !isset($this->ingredient)){
            $d['IngredientLists']=$this->IngredientList->readSandwich($this->sandwich);
        }
        else{
            $d['IngredientLists']= $this->IngredientList->readAll();
        }
        print_r(json_encode($d));
    }
}
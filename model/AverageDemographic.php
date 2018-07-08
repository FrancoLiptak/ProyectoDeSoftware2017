<?php

class AverageDemographic{
    private $name;
    private $value;


    public function __construct($name, $value){
        $this->value = round($value,2);
        $this->name = $name;
    }

    //Setters

    public function setname($name){
        $this->name = $name;
    }

    public function setvalue($value){
        $this->value = $value;
    }


    //Getters

    public function getname() {
        return $this->name;
    }

    public function getvalue() {
        return $this->value;
    }

}
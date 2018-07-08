<?php

class DataDemographic {
    private $id;
    private $fridge;
    private $heating;
    private $electricity;
    private $typeOfHousing;
    private $pet;
    private $typeOfWater;

    public function __construct($id, $electricity, $fridge, $pet, $typeOfHousing, $heating, $typeOfWater){
        $this->id = $id;
        $this->electricity = $electricity;
        $this->fridge = $fridge;
        $this->pet = $pet;
        $this->typeOfHousing = $typeOfHousing;
        $this->heating = $heating;
        $this->typeOfWater = $typeOfWater;
    }
    
    public function getFridge() {
        return $this->fridge;
    }
    
    public function getHeating() {
        return $this->heating['nombre'];
    }
    
    public function getElectricity() {
        return $this->electricity;
    }
    
    public function getTypeOfHousing() {
        return $this->typeOfHousing['nombre'];
    }
    
    public function getPet() {
        return $this->pet;
    }
    
    public function getTypeOfWater() {
        return $this->typeOfWater['nombre'];
    }
    
    public function setFridge($fridge){
        $this->fridge = $fridge;
    }
    
    public function setHeating($heating){
        $this->heating = $heating;
    }
    
    public function setElectricity($electricity){
        $this->electricity = $electricity;
    }
    
    public function setTypeOfHousing($typeOfHousing){
        $this->typeOfHousing = $typeOfHousing;
    }
    
    public function setPet($pet){
        $this->pet = $pet;
    }
    
    public function setTypeOfWater($typeOfWater){
        $this->typeOfWater = $typeOfWater;
    }
}
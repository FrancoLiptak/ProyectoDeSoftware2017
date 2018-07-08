<?php

class Promedio{
    
    private $x;
    private $y1;
    private $y2;
    private $y3;
    private $y4;
    private $y5;

    public function __construct($x, $y1, $y2, $y3, $y4, $y5){
        $this->x = $x;
        $this->y1 = $y1;
        $this->y2 = $y2;
        $this->y3 = $y3;
        $this->y4 = $y4;
        $this->y5 = $y5;
    }

    //Setters

    public function setx($x){
        $this->x = $x;
    }

    public function sety1($y1){
        $this->y1 = $y1;
    }

    public function sety2($y2){
        $this->y2 = $y2;
    }

    public function sety3($y3){
        $this->y3 = $y3;
    }

    public function sety4($y4){
        $this->y4 = $y4;
    }

    public function sety5($y5){
        $this->y5 = $y5;
    }

    //Getters
    public function getx(){
        return $this->x;
    }

    public function gety1() {
        return $this->y1;
    }

    public function gety2() {
        return $this->y2;
    }
    
    public function gety3() {
        return $this->y3;
    }
    
    public function gety4() {
        return $this->y4;
    }

    public function gety5() {
        return $this->y5;
    }
    
}
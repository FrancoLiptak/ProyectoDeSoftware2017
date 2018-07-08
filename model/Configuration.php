<?php

class Configuration{
    private $id;
    private $nombre;
    private $valor;


    public function __construct($id, $nombre, $valor){
        $this->nombre = trim($nombre);
        $this->valor = trim($valor);
        $this->id = $id;
    }

    //Setters

    public function setId($id){
        $this->id = $id;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setvalor($valor){
        $this->valor = $valor;
    }

    //Getters

    public function getNombre() {
        return $this->nombre;
    }

    public function getValor() {
        return $this->valor;
    }
}
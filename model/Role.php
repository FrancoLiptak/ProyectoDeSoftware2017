<?php

class Role{
    private $id;
    private $nombre;


    public function __construct($id, $nombre){
        $this->id = $id;
        $this->nombre = $nombre;
    }

    //Setters

    public function setId($id){
        $this->id = $id;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    //Getters
    public function getId($id){
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }
}
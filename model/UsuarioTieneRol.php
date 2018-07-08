<?php

class UsuarioTieneRol{
    private $usuario_id;
    private $rol_id;


    public function __construct($usuario_id, $rol_id){
        $this->ususario_id = $ususario_id;
        $this->rol_id = $rol_id;
    }

    //Setters

    public function setUsuarioId($usuario_id){
        $this->usuario_id = $usuario_id;
    }

    public function setRolId($rol_id){
        $this->rol_id = $rol_id;
    }

    //Getters
    public function getUsuarioId($usuario_id){
        return $this->usuario_id;
    }

    public function getRolId() {
        return $this->rol_id;
    }

    public function getArray(){
        echo $this->usuario_id;
        echo $this->rol_id;
        return array($this->usuario_id, $this->rol_id);        
    }
}
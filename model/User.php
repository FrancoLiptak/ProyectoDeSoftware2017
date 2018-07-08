<?php

class User {

    private $id;
    private $email;
    private $userName;
    private $password;
    private $updatedAt;
    private $createdAt;
    private $activo;
    private $firstName;
    private $lastName;
    private $borrado;
    private $roles;

    
    public function __construct($id, $email, $userName, $password, $updatedAt, $createdAt, $activo, $firstName, $lastName, $borrado, $roles){
        $this->id = $id;
        $this->email = $email;
        $this->userName = $userName;
        $this->password = $password;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
        $this->activo = $activo;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->borrado = $borrado;
        $this->roles = $roles;
    }

//GETTERS

    public function getId(){
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getActivo() {
        return $this->activo;
    }

     public function getBorrado() {
        return $this->borrado;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

//SETTERS

     public function setId($id){
        $this->id = $id;
     }

     public function setEmail($email) {
         $this->email = $email;
    }

    public function setUserName($userName) {
         $this->userName = $userName;
    }

    public function setPassword($password) {
         $this->password = $password;
    }

    public function setUpdatedAt($updatedAt) {
         $this->updatedAt = $updatedAt;
    }

    public function setCreatedAt($createdAt) {
         $this->createdAt = $createdAt;
    }

    public function setActivo($activo) {
         $this->activo = $activo;
    }

    public function setBorrado($borrado) {
         $this->borrado = $borrado;
    }

    public function setFirstName($firstName) {
         $this->firstName = $lastName;
    }

    public function setLastName($lastName) {
         $this->lastName = $lastName;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
    }


}

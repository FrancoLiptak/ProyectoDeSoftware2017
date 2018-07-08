<?php

class Patient{
    
    private $id;
    private $lastName;
    private $name;
    private $birthday;
    private $documentType;
    private $documentNumber;
    private $address;
    private $phone;
    private $healthInsurance;
    private $gender;
    private $dataDemographic;

    public function __construct($id, $lastName, $name, $address, $phone, $birthday, $gender, $healthInsurance, $documentType, $documentNumber, $dataDemographic = null){
        $this->id = $id;
        $this->lastName = $lastName;
        $this->name = $name;
        $this->birthday = $birthday;
        $this->documentType = $documentType;
        $this->documentNumber = $documentNumber;
        $this->address = $address;
        $this->phone = $phone;
        $this->healthInsurance = $healthInsurance;
        $this->gender = $gender;
        $this->dataDemographic = $dataDemographic;
    }

    //Setters

    public function setId($id){
        $this->id = $id;
    }

    public function setLastName($lastName){
        $this->lastName = $lastName;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setBirthday($birthday){
        $this->birthday = $birthday;
    }

    public function setDocumentType($documentType){
        $this->documentType = $documentType;
    }

    public function setDocumentNumber($documentNumber){
        $this->documentNumber = $documentNumber;
    }

    public function setAddress($address){
        $this->address = $address;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function setHealthInsurance($healthInsurance){
        $this->healthInsurance = $healthInsurance;
    }

    public function setGender($gender){
        $this->gender = $gender;
    }

    public function dataDemographic($dataDemographic){
        $this->dataDemographic = $dataDemographic;
    }

    //Getters
    public function getId(){
        return $this->id;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getBirthday() {
        return $this->birthday;
    }
    
    public function getDocumentType() {
        return $this->documentType['nombre'];
    }

    public function getDocumentNumber() {
        return $this->documentNumber;
    }
    
    public function getAddress() {
        return $this->address;
    }

    public function getPhone() {
        return $this->phone;
    }
    
    public function getHealthInsurance() {
        return $this->healthInsurance['nombre'];
    }

    public function getGender() {
        return $this->gender;
    }
    
    public function getDataDemographic(){
        return $this->dataDemographic;
    }
}
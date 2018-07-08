<?php

include_once "TwigController.php";
include_once "validator.php";
include_once "model/PatientRepository.php";
include_once 'IndexController.php';

Class ListPatientController{
    private static $instance;


    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function basicView(){
        if(Validator::checkPermissions('paciente_index')){ 
            $array = Validator::navData() + array('isAdmin' => Validator::checkPermissions('paciente_destroy')) + array( 'patients' => $this->listPatients());
            echo TwigController::getTwig()->render('listPatient.html.twig', $array);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    private function listPatients(){
        return PatientRepository::getInstance()->listAll();
    }
    
    
}
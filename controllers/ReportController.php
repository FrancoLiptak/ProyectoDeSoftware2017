<?php
include_once "validator.php";
include_once "IndexController.php";
include_once "model/HealthControlsRepository.php";
include_once "model/PromedioRepository.php";
include_once "model/PatientRepository.php";

Class ReportController{
    private static $instance;

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function makeList(){

    }

    public function basicView(){
        if(Validator::checkPermissions('control_salud_index') && isset($_GET['idPatient'])){            
            $array = Validator::navData() + 
                ['controls' => HealthControlsRepository::getInstance()->listAllFrom($_GET['idPatient']),
                'paciente' => PatientRepository::getInstance()->getName($_GET['idPatient'])
                ] + PromedioRepository::getInstance()->listAll(); 
            echo TwigController::getTwig()->render('patientReport.html.twig', $array);
        }elseif (Validator::checkPermissions('paciente_index')){
            $array = Validator::navData() + PatientRepository::getInstance()->averageDemographicList();
            echo TwigController::getTwig()->render('demographicReport.html.twig', $array);
        }else{
            IndexController::getInstance()->basicView();            
        }
    }

}
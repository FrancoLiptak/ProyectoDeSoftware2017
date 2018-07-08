<?php
include_once "TwigController.php";
include_once "model/PatientRepository.php";
include_once "validator.php";
include_once "ListPatientController.php";
include_once "model/RoleRepository.php";
include_once "IndexController.php";
include_once "model/Resource.php";

Class RegisterPatientController{
    private static $instance;

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function basicView(){
        if(Validator::checkPermissions('paciente_new')){
            $array = Validator::navData() + array('isAdmin' => Validator::checkPermissions('paciente_destroy'), 'recursos' => $this->getResources());
            echo TwigController::getTwig()->render('registerPatient.html.twig', $array);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    private function getResources(){
        $res = Resource::getAllResources();

        $resources = [];

        array_push( $resources, $res[0] );
        array_push( $resources, $res[1] );
        array_push( $resources, $res[2] );
        array_push( $resources, $res[3] );
        array_push( $resources, $res[4] );

        return $resources;
        
    }

    public function errorView($message){
        echo TwigController::getTwig()->render(
            'registerPatient.html.twig',
            Validator::navData() + ['roles' => RoleRepository::getInstance()->listAll()] + ['errorMessage' => $message]);    
    }

    public function createPatient(){

        if(Validator::checkPermissions('paciente_new')){
            $dataPatient = array(
                ":last_name" => $_POST['last-name'],
                ":first_name" => $_POST['first-name'],
                ":address" => $_POST['address'],
                ":phone" => $_POST['phone'],
                ":gender" => $_POST['gender'],
                ":birthday" => $_POST['birthday'],
                ":health_insurance" => $_POST['health-insurance'],
                ":document_type" => $_POST['document-type'],
                ":document_number" => $_POST['document-number'],
            );

            $dataDemographic = array(
                ":fridge" => $_POST['fridge'],
                ":electricity" => $_POST['electricity'],
                ":type_of_heating" => $_POST['type-of-heating'],
                ":type_of_housing" => $_POST['type-of-housing'],
                ":pet" => $_POST['pet'],
                ":type_of_water" => $_POST['type-of-water'],
            );

            if(Validator::validateArray($dataPatient) && Validator::validateArray($dataDemographic)){
                if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,64}]*$/",$dataPatient[':last_name'])) {
                    $this->errorView('El apellido debe contener solo letras y espacios en blanco');                        
                }else{
                    if (!preg_match("/^[[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}]*$/",$dataPatient[':first_name'])) {
                        $this->errorView('El nombre debe contener solo letras y espacios en blanco');                        
                    }else{
                        if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $dataPatient[':birthday'])) {
                            $this->errorView("La fecha de nacimiento debe tener formato MM/DD/AAAA");
                        }else{
                                if (!is_numeric($dataPatient[':document_number'])) {
                                    $this->errorView('En el campo "Número de documento" solo se aceptan números.');
                                }else{
                                    PatientRepository::getInstance()->insert($dataPatient, $dataDemographic);
                                    ListPatientController::getInstance()->basicView();

                                }
                            }
                        }
                    }
                
                }
            }else{
                $this->errorView("No tiene permisos para crear un paciente.");;
            }
        }
    


}
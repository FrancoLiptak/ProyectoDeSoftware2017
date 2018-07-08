<?php
include_once "TwigController.php";
include_once "model/PatientRepository.php";
include_once "validator.php";
include_once "IndexController.php";
include_once "ListPatientController.php";
include_once "model/RoleRepository.php";

Class PerfilPatientController{
    private static $instance;
    private static $idPatient;

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function basicView(){

        if(Validator::checkPermissions('paciente_index')){
            $array = Validator::navData() + array('isAdmin' => Validator::checkPermissions('paciente_destroy'), 'recursos' => $this->getResources()) + array( 'patient' => $this->listInformation());
            echo TwigController::getTwig()->render('perfilPatient.html.twig', $array);
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
            'perfilPatient.html.twig',
            Validator::navData() + ['roles' => RoleRepository::getInstance()->listAll()] + ['errorMessage' => $message]);    
    }
    
    private function listInformation(){
        return PatientRepository::getInstance()->getPatient($_GET['idPatient']);
    }

    public function deletePatient(){
        if(Validator::checkPermissions('paciente_destroy')){
            PatientRepository::getInstance()->deletePatient($_POST['deleteId']);
            ListPatientController::getInstance()->basicView();
        }else{
            echo "No tiene permisos para eliminar.";
        }
    }

    public function editPatient(){
        if(Validator::checkPermissions('paciente_update')){

            $editPatientArray = array(
                "last_name" => $_POST['editLastName'],
                "first_name" => $_POST['editName'],
                "address" => $_POST['editAddress'],
                "phone" => $_POST['editPhone'],
                "birthday" => $_POST['editBirthday'],
                "health_insurance" => $_POST['editHealthInsurance'],
                "gender" => $_POST['editGender'],
                "document_type" => $_POST['editTypeDocument'],
                "document_number" => $_POST['editNumberDocument'],
            );

            $dataDemographic = array(
                "fridge" => $_POST['editFridge'],
                "electricity" => $_POST['editElectricity'],
                "type_of_heating" => $_POST['editHeating'],
                "type_of_housing" => $_POST['editTypeOfHousing'],
                "pet" => $_POST['editPet'],
                "type_of_water" => $_POST['editTypeOfWater']
            );

            if(Validator::validateArray($editPatientArray) && Validator::validateArray($dataDemographic)){
                if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,64}]*$/",$editPatientArray['last_name'])) {
                    $this->errorView('El apellido debe contener solo letras y espacios en blanco');                        
                }else{
                    if (!preg_match("/^[[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}]*$/",$editPatientArray['first_name'])) {
                        $this->errorView('El nombre debe contener solo letras y espacios en blanco');                        
                    }else{
                        if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $editPatientArray['birthday'])) {
                            $this->errorView("La fecha de nacimiento debe tener formato MM/DD/AAAA");
                        }else{
                            if (!is_numeric($editPatientArray['document_number'])) {
                                $this->errorView('En el campo "Número de documento" solo se aceptan números.');
                            }else{
                                PatientRepository::getInstance()->editPatient($editPatientArray, $dataDemographic, $_POST['editId']);
                                ListPatientController::getInstance()->basicView();
                            }
                        }

                    }
                }
            } 
        }else{
            echo "No tiene permisos para editar un paciente.";
        }
    }
    

}
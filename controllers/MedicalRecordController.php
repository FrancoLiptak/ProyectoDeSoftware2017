<?php

include_once './model/HealthControlsRepository.php';
include_once './controllers/validator.php';
include_once 'TwigController.php';
include_once 'PerfilPatientController.php';
include_once './model/UserRepository.php';

class MedicalRecordController {
    private static $instance;
    
    public static function getInstance() {        
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function basicView(){
        if(isset($_GET['idPatient']) && Validator::checkPermissions('control_salud_index')){
            $array = Validator::navData() + [
                'isAdmin' => Validator::checkPermissions('usuario_destroy'),
                'healthControls' => HealthControlsRepository::getInstance()->listAllFrom($_GET['idPatient']),
                'canDelete' => Validator::checkPermissions('control_salud_destroy'),
                'paciente' => PatientRepository::getInstance()->getName($_GET['idPatient']),
                'paciente_id' => $_GET['idPatient']
            ];
            echo TwigController::getTwig()->render('listHistoriasClinicas.html.twig', $array);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    public function deleteMedicalRecord(){
        if(Validator::checkPermissions('control_salud_destroy')){
            HealthControlsRepository::getInstance()->deleteControlSalud($_POST['deleteId']);
            header('Location: '."?controlador=medicalRecord&idPatient=".$_POST['patientId']);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    public function editMedicalRecord(){
        if(Validator::checkPermissions('control_salud_update')){
            $data = array(
                ":fecha" => $_POST['date'],
                ":pesa" => $_POST['weight'],
                ":vacunas_completas" => $_POST['vaccine'],
                ":vacunas_observaciones" => $_POST['vaccine-observations'],
                ":maduracion_acorde" => $_POST['maduration'],
                ":maduracion_observaciones" => $_POST['maduration-observations'],
                ":ex_fisico_normal" => $_POST['phisicalTest'],
                ":ex_fisico_observaciones" => $_POST['phisicalTest-observations'],
                ":pc" => $_POST['PC'],
                ":ppc" => $_POST['PPC'],
                ":talla" => $_POST['height'],
                ":alimentacion" => $_POST['feeding'],
                ":observaciones_generales" => $_POST['observations'],
                ":id" => $_POST['editId']
            );

            if(Validator::validateArray($data)){
                if ($this->validateDate($data[':fecha'])
                    && is_numeric($data[':pesa'])
                    && ($data[':vacunas_completas'] == 0 || $data[':vacunas_completas'] == 1)
                    && ($data[':maduracion_acorde'] == 0 || $data[':maduracion_acorde'] == 1)
                    && ($data[':ex_fisico_normal'] == 0 || $data[':ex_fisico_normal'] == 1)
                    && is_numeric($data[':pc'])
                    && is_numeric($data[':ppc'])
                    && is_numeric($data[':talla'])) {
                        HealthControlsRepository::getInstance()->editControlSalud($data);
                }
            }
            header('Location: '."?controlador=medicalRecord&idPatient=".$_POST['patientId']);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        date_default_timezone_set('UTC');
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function createMedicalRecord(){
        if(Validator::checkPermissions('control_salud_update')){            
            date_default_timezone_set('UTC');
            $date = date("Y-m-d");
            $data = array(
                ":fecha" => $date,
                ":pesa" => $_POST['weight'],
                ":vacunas_completas" => $_POST['vaccine'],
                ":vacunas_observaciones" => $_POST['vaccine-observations'],
                ":maduracion_acorde" => $_POST['maduration'],
                ":maduracion_observaciones" => $_POST['maduration-observations'],
                ":ex_fisico_normal" => $_POST['phisicalTest'],
                ":ex_fisico_observaciones" => $_POST['phisicalTest-observations'],
                ":pc" => $_POST['PC'],
                ":ppc" => $_POST['PPC'],
                ":talla" => $_POST['height'],
                ":alimentacion" => $_POST['feeding'],
                ":observaciones_generales" => $_POST['observations'],
                ":paciente_id" => $_POST['patientId'],
                ":user_id" => UserRepository::getInstance()->getIdUser(Validator::username())
            );

            if(Validator::validateArray($data)){
                if ($this->validateDate($data[':fecha'])
                    && is_numeric($data[':pesa'])
                    && ($data[':vacunas_completas'] == 0 || $data[':vacunas_completas'] == 1)
                    && ($data[':maduracion_acorde'] == 0 || $data[':maduracion_acorde'] == 1)
                    && ($data[':ex_fisico_normal'] == 0 || $data[':ex_fisico_normal'] == 1)
                    && is_numeric($data[':pc'])
                    && is_numeric($data[':ppc'])
                    && is_numeric($data[':talla'])) {
                        HealthControlsRepository::getInstance()->createControlSalud($data);
                }
            }
            header('Location: '."?controlador=medicalRecord&idPatient=".$_POST['patientId']);
        }else{
            IndexController::getInstance()->basicView();
        }        
    }
}
<?php
include_once './model/ConfigurationRepository.php';
include_once './controllers/validator.php';
include_once 'TwigController.php';
include_once 'SessionController.php';

class ConfigurationController {
    private static $instance;
    
    public static function getInstance() {        
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function baseView() {

        if(Validator::checkPermissions('configuration')){            
            $array = Validator::navData() + array('isAdmin' => Validator::checkPermissions('paciente_destroy'));
            echo TwigController::getTwig()->render('configuration.html.twig', $array);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    private function errorView($message) {
        echo TwigController::getTwig()->render('configuration.html.twig', Validator::navData() + $array = Validator::navData() + array('isAdmin' => Validator::checkPermissions('paciente_destroy')) + ['errorMessage' => $message]);
    }

    public function update() {
        if(Validator::checkPermissions('configuration')){
            $dataArray = array(
                'title' => $_POST['title'],
                'email' => $_POST['email'],
                'description' => $_POST['description'],
                'pageSize' => $_POST['pageSize'],
                'maintenance' => $_POST['maintenance'],
            );

            if (Validator::validateArray($dataArray)){
                if (!preg_match("/^[a-zA-Z .,]*$/",$dataArray['title'])) {
                    $this->errorView("El titulo solo debe contener letras, '.', ',' y espacios en blanco");
                }else{
                    if (!filter_var($dataArray['email'], FILTER_VALIDATE_EMAIL)) {
                        $this->errorView('Formato de email invalido');
                    }else{
                        if (!is_numeric($dataArray['pageSize'])) {
                            $this->errorView('El tamaño de pagina debe ser un numero');
                        }else{
                            if ($dataArray['pageSize'] <= 0) {
                                $this->errorView('El tamaño de pagina debe ser un mayor a 0');
                            }else{
                                ConfigurationRepository::getInstance()->save($dataArray);
                                $this->baseView();                
                            }
                        }
                    }
                }
            }else{
                $this->errorView('Debe completar todos los campos');
            }
        }else{
            IndexController::getInstance()->basicView();
        }
    }
} 
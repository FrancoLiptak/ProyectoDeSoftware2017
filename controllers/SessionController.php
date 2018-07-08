<?php
//include_once '../model/UserModule.php';
include_once './controllers/validator.php';
include_once 'TwigController.php';
include_once './model/UserRepository.php';
include_once 'IndexController.php';

class SessionController {
    private static $instance;

    public static function getInstance() {        
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function login() {
        if(Validator::validateLogin()){
            IndexController::getInstance()->basicView();
        }else{
            echo TwigController::getTwig()->render(
                    'login.html.twig',
                    Validator::navData());    
        }
    }

    public function checkLogin() {
        Validator::starSession();

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $authed = Validator::paswordAuth($username, $password);

            if ($authed) {
                $_SESSION['username'] = $username;
                $_SESSION['userId'] = UserRepository::getInstance()->getIdUser($username)[0];
                IndexController::getInstance()->basicView();
            } else {
                $this->loginFailed('Usuario o contraseña incorrectos. También recuerde que solo pueden iniciar sesión usuarios activos.');
            }
        }
    }

    private function loginFailed($message){
        echo TwigController::getTwig()->render(
            'login.html.twig',
            Validator::navData() + ['errorMessage' => $message]);    
}

    public function closeSession(){
        Validator::starSession();
        $_SESSION = [];
        IndexController::getInstance()->basicView();
    }
}
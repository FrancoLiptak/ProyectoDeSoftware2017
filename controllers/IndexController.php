<?php
include_once "TwigController.php";
include_once "validator.php";


Class IndexController{
    private static $instance;

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function basicView(){
        $array = Validator::navData() + array('isAdmin' => Validator::checkPermissions('paciente_destroy'));
        echo TwigController::getTwig()->render('index.html.twig', $array); 
    }
    
}
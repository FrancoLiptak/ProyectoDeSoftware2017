<?php

class TwigController{
    private static $instance;
    
    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getTwig(){
        require_once './vendor/autoload.php';
        $loader = new Twig_Loader_Filesystem('views/home');
        $twig = new Twig_Environment($loader);
        return $twig;
    }
}
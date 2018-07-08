<?php
class FrontController{

    static function main(){

        //Formo el nombre del Controlador. Si no hay controlado, se queda con IndexController
        if(! empty($_GET['controlador']))
            $controllerName = ucfirst($_GET['controlador']) . 'Controller'; //Todos los controladores terminan con 'Controller' | ucfirst pasa la primer letra a mayúscula.
        else{
            $controllerName = "IndexController";
        }
 
        //Lo mismo sucede con las acciones, si no hay acción, tomamos index como acción
        if(! empty($_GET['accion']))
              $actionName = $_GET['accion'];
        else
              $actionName = "basicView";
 
        require_once "controllers/$controllerName.php"; //Requiero el controlador indicado.

        //Creo una instancia del controlador y llamo a la acción
        $controller = $controllerName::getInstance();
        $controller->$actionName();
    }
}

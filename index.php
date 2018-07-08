<?php
//En MVC, todos los requerimientos pasan por index.php. Éste delega las tareas a FrontController.

require_once "./controllers/FrontController.php"; //Incluyo el FrontController

FrontController::main(); //Invoco el FrontController con el método main.

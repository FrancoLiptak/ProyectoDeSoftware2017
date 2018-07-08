<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once '../vendor/autoload.php';
require_once '../controllers/TurnController.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($config);

$app->get('/turnos/{fecha}', function ($request, $response, $args) {

    return $response->withJson(TurnController::getTurns( $args['fecha'] ) );

});

$app->post('/turnos/{dni}/fecha/{fecha}/hora/{hora}', function ($request, $response, $args) {
   
    $dni = $request->getAttribute('dni');
    $fecha = $request->getAttribute('fecha');
    $hora = $request->getAttribute('hora');
    return $response->withJson( TurnController::reserveTurn( $dni, $fecha, $hora ) );
    
});

$app->run();




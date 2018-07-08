<?php

include_once "../model/TurnRepository.php";

class TurnController{

    public static function getTurns($fecha){
        
        date_default_timezone_set("America/Argentina/Buenos_Aires");

        $fecha = date('Y-m-d',strtotime($fecha));
        $hoy = date('Y-m-d');
        if($fecha < $hoy){
            return "La fecha ingresada ya pasó";
        }

        $now = date("H:i:s");
        $totalTurns = TurnRepository::getInstance()->getPossibleTurns($now); // Obtengo los turnos disponibles desde el momento del día actual.
        $reservedTurns = TurnRepository::getInstance()->getReservedTurns($fecha);

        $availableTurns = [];
        foreach($totalTurns as $turn){ 
            if(!(array_search($turn['horario'], $reservedTurns) !== false)){
                array_push($availableTurns, $turn);
            }       
        }

        if ( count($availableTurns) > 0 ){
            return $availableTurns;
        }else{
            return "No hay turnos disponibles para la fecha solicitada";
        }
    }

    public static function reserveTurn( $dni, $fecha, $hora ){

        if(!isset($dni)){
            return "No ha ingresado un DNI válido";
        }

        //Chequeo la fecha ingresada.
        $fecha = date('Y-m-d',strtotime($fecha));
        $hoy = date('Y-m-d');
        if($fecha < $hoy){
            return "La fecha ingresada ya pasó";
        }

        //Chequeo la hora ingresada.
        $hora = date("H:i:s", strtotime($hora));
        $ahora = date("H:i:s");
        if($hora < $ahora){
            return "La hora ingresada ya pasó.";
        }

        $h = date('H', strtotime($hora));
        $m = date('i', strtotime($hora));
        if($m>30){
            $m = "00";
            $h += 1;
        }elseif ($m<30 && $m != 0){
            $m = "30";
        }
        $horario = date('H:i:s', strtotime("$h:$m:00"));

        $totalTurns = TurnRepository::getInstance()->getAllTurns();
        if(!(array_search($horario, $totalTurns) !== false)){
            return "Debe elejir un turno en la franja horaria de 8 a 20hs";
        }
        

        $reservedTurn = TurnRepository::getInstance()->getReservedTurns($fecha, $horario);

        if(count($reservedTurn) > 0 ){
            return "El turno ya está seleccionado";
        }else{
            $resultado = TurnRepository::getInstance()->reserveTurn($dni, $fecha, $horario); // cambiar por id

            if($resultado === true){
                return TurnRepository::getInstance()->getIdTurn($fecha, $horario);
            }else{
                return "Ha habido un error. Por favor, reintente mas tarde.";
            }
        }

    }
}
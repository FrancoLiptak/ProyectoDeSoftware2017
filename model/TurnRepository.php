<?php

include_once "PDORepository.php";

class TurnRepository extends PDORepository{

    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }

    public function getReservedTurns($date, $hour = null){

        if($hour != null){
            $sql = "SELECT horario FROM turno WHERE dia=? AND horario=?;";
            return $this->queryList($sql, [$date, $hour], function($row){return $row['horario'];});
        }else{
            $sql = "SELECT horario FROM turno WHERE dia=? ORDER BY horario;";
            return $this->queryList($sql, [$date], function($row){return $row['horario'];});
        }

    }

    public function getPossibleTurns($now){
        $sql = "SELECT horario FROM horarios_posibles WHERE horario>?;";

        return $this->getInformation($sql, [$now]);

    }

    public function getAllTurns(){
        $sql = "SELECT horario FROM horarios_posibles;";

        return $this->queryList($sql, [], function($row){return $row['horario'];});

    }

    public function reserveTurn( $dni, $date, $hour ){
        $sql = "INSERT INTO turno (dni, dia, horario) VALUES (:dni, :dia, :hora);";

        $args = array(
            ':dni' => $dni,
            ':dia' => $date,
            ':hora' => $hour
        );

        return $this->execQuery($sql, $args);
    }

    public function getIdTurn($fecha, $hora){
        $sql = "SELECT id FROM turno WHERE dia=? AND horario=?;";

        return $this->queryFirst($sql, [$fecha, $hora], function($row){return $row['id'];});

    }
}
<?php
include_once "PDORepository.php";
include_once "Configuration.php";

class ConfigurationRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }

    public function listAll() {

        $mapper = function($row) {
            $resource = new Configuration($row['id_configuracion'], $row['nombre'], $row['valor']);
            return $resource;
        };

        $answer = $this->queryList(
                "SELECT * FROM configuracion", [], $mapper);

        return $answer;
    }

    public function get($arg){
        $mapper = function($row) {
            $resource = new Configuration($row['id_configuracion'], $row['nombre'], $row['valor']);
            return $resource;
        };

        $answer = $this->queryList(
                "SELECT * FROM configuracion WHERE nombre = ?", [$arg], $mapper);

        return $answer;        
    }

    public function save($dataArray) {
        
        $mapper = function($row) {
        };
        
        foreach ($dataArray as $nombre => $valor){
            $this->queryList(
                "UPDATE configuracion
                SET valor = :valor
                WHERE nombre = :nombre;", [':nombre' => $nombre, ':valor' => $valor], $mapper);
        }

    }
}
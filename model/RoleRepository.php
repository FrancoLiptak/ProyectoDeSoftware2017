<?php
include_once "PDORepository.php";
include_once "Role.php";

class RoleRepository extends PDORepository {

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
            $resource = new Role($row['id'], $row['nombre']);
            return $resource;
        };

        $answer = $this->queryList(
                "SELECT * FROM rol;", [], $mapper);

        return $answer;
    }
}
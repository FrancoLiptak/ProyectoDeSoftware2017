<?php

abstract class PDORepository {
    
    private $connection;
/*    const USERNAME = "root";
    const PASSWORD = "pass";
	const HOST ="db";
	const DB = "grupo11";
*/
    const USERNAME = "grupo11";
    const PASSWORD = "YjIyNzg0MzRjYjk4";
	const HOST ="localhost";
    const DB = "grupo11";
  
    
    private function getConnection(){

        if(!isset($connection)){
            $u=self::USERNAME;
            $p=self::PASSWORD;
            $db=self::DB;
            $host=self::HOST;
            $connection = new PDO("mysql:dbname=$db;host=$host;port=3306;charset=UTF8", $u, $p);
        }

        return $connection;
        
    }
    
    public function connection(){
        return $this->getConnection();
    }

    protected function queryList($sql, $args, $mapper){

        $stmt = $this->executeQuery($sql, $args);

        $list = [];
        while($element = $stmt->fetch()){
            $list[] = $mapper($element);
        }
        return $list;
    }

    protected function queryMap($sql, $args, $mapper){
        
        $stmt = $this->executeQuery($sql, $args);

        while($element = $stmt->fetch()){
            $mapper($element);
        }
    }
        
    protected function execQueryList($sql, $mapper){
        
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        $list = [];
        while($element = $stmt->fetch()){
            $list[] = $mapper($element);
        }
        return $list;
    }

    protected function executeQuery($sql, $args){

        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    protected function execQuery($sql, $args){
        
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        return $stmt->execute($args);
    }

    protected function insertAndGetLastId($sql, $args, &$lastId = null){
        
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($args);
        $lastId = $connection->lastInsertId();

        return $stmt;
        
    }

    protected function queryFirst($sql, $args, $mapper){
        
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($args);
        return $mapper($stmt->fetch());
    }

    protected function queryId($sql, $args){
        
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetch();
    }
    
    protected function getInformation($sql, $args = null){
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);

        if($args == null){
            $stmt->execute();
        }else{
            $stmt->execute($args);
        }
        return $stmt->fetchAll();
    }
}

<?php
include_once "PDORepository.php";
include_once "User.php";
include_once "UsuarioTieneRol.php";
include_once "Role.php";

class UserRepository extends PDORepository {

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
            $segundoMapper = function($row) {
                return $row['nombre'];
            };

            $roles = $this->queryList(
                "SELECT nombre
                FROM usuario_tiene_rol INNER JOIN rol ON (usuario_tiene_rol.rol_id = rol.id) 
                WHERE  usuario_tiene_rol.usuario_id = ?;", [$row['id']], $segundoMapper
            );

            $resource = new User($row['id'], $row['email'], $row['username'], $row['password'], $row['updated_at'], $row['created_at'], $row['activo'], $row['first_name'], $row['last_name'], $row['borrado'], $roles);
            return $resource;
        };

        $answer = $this->queryList(
                "SELECT * FROM usuario WHERE borrado=?;", ['0'], $mapper);

        return $answer;
    }

    public function pass($username) {
        
        $mapper = function($row) {
            return $row['password'];
        };
        
        // Si no está activo no se puede loguear.
        
        $answer = $this->queryFirst(
                "SELECT password FROM usuario WHERE activo = 1 AND username=?;", [$username], $mapper);

        return $answer;
    }

    public function deleteUser($id){

        $mapper = function($row){
        };

        $sql =  "UPDATE usuario
                SET borrado=1
                WHERE id = ?";
        
        $aux = $this->queryList($sql, [$id], $mapper);

    }

    public function isAdmin($username) {
        return true;
    }

    public function canDo($username, $action) {

        $mapper = function($row) {
            return true;
        };
    
        $answer = $this->queryList(
                "SELECT * 
                FROM ((((usuario INNER JOIN usuario_tiene_rol ON usuario.id = usuario_tiene_rol.usuario_id)
                        INNER JOIN rol ON usuario_tiene_rol.rol_id = rol.id)
                        INNER JOIN rol_tiene_permiso ON rol.id = rol_tiene_permiso.rol_id)
                        INNER JOIN permiso ON rol_tiene_permiso.permiso_id = permiso.id)
                WHERE usuario.username=? and permiso.nombre=?;",
                [$username, $action], $mapper);

        if(count($answer) > 0){
            return true;            
        }else{
            return false;
        }
    }

    public function insert($dataArray, $roles) {

        $mapper = function($row) {
        };

        //guardar el nuevo usuario
        $this->queryList(
                "INSERT INTO usuario (email, username, password, updated_at, created_at, activo, first_name, last_name, borrado)
                VALUES (:email, :username, :password, :updated_at, :created_at, :activo, :first_name, :last_name, :borrado);",
                $dataArray, $mapper);

        $mapper = function($row) {
            $resource = [$row['usuario_id'], $row['rol_id']];
            return $resource;
        };

        //relacion con rol
        foreach ($roles as $role){
            $this->queryList(
                "INSERT INTO usuario_tiene_rol (usuario_id, rol_id)
                VALUES (?, ?);",
                array_pop($this->queryList(
                        "SELECT usuario.id as usuario_id, rol.id as rol_id
                        FROM usuario, rol
                        WHERE usuario.username = ? and rol.nombre = ?",
                        [$dataArray[':username'], $role], $mapper)),
                $mapper);        
        }
    }

    public function myRoles($id){
        $sql = "SELECT rol.nombre
                FROM usuario inner join usuario_tiene_rol on (usuario.id = usuario_tiene_rol.usuario_id)
                             inner join rol on (usuario_tiene_rol.rol_id = rol.id) 
                WHERE  usuario.id = ? ";

        $mapper = function($row) {
                        $resource = new Role(row['id'],row['nombre']);
                        return $resource;
                  };


       $answer =  $this->queryList($sql,['$id'],$mapper);
       return $answer;

    }

    public function editUser($dataArray, $roles, $idUser, $editPass, $editActivo) {

        $mapper = function($row) {
        };

        $sql = "UPDATE usuario
        SET email = :email,
            username = :user_name,
            updated_at = :updated_at,
            first_name = :name, 
            last_name = :last_name";

        // Por defecto no actualiza ni la contraseña, ni el estado del usuario.

        if( $editPass == true ){ // Si actualizó la pass, le concateno la pass.
            $sql = $sql . ", password = :password";
        }

        if( $editActivo == true ){ // Si cambió el estado, le concateno el estado.
            $sql = $sql . ", activo = :activo";
        }
        
        $sql = $sql . " WHERE id = '$idUser'"; // Finalmente concateno la condición final.
        $this->queryList($sql, $dataArray, $mapper);

        $mapper = function($row) {
            $resource = [$row['usuario_id'], $row['rol_id']];
            return $resource;
        };

        // Elimino los roles que tenía hasta el momento

        $sql = "DELETE FROM usuario_tiene_rol WHERE usuario_id = '$idUser'";
        
        $connection = $this->connection();
        $connection->exec($sql);

        // Pongo los nuevos roles

        foreach ($roles as $role){
            $this->queryList(
                "INSERT INTO usuario_tiene_rol (usuario_id, rol_id)
                VALUES (?, ?);",
                array_pop($this->queryList(
                        "SELECT usuario.id as usuario_id, rol.id as rol_id
                        FROM usuario, rol
                        WHERE usuario.username = ? and rol.nombre = ?",
                        [$dataArray['user_name'], $role], $mapper)),
                $mapper);        
        }
    }

    public function usernameExists($username){
        $mapper = function($row) {
            return true;
        };

        $answer = $this->queryList(
                "SELECT * 
                FROM usuario
                WHERE username=?;",
                [$username], $mapper);

        if(count($answer) > 0){
            return true;            
        }else{
            return false;
        }
    }

    public function usernameExistsChange($username, $idUser){

        $mapper = function($row) {
            return true;
        };

        $sql = "SELECT * FROM usuario WHERE username=? and id <> ?;";

        $answer = $this->queryList($sql, [$username, $idUser], $mapper);

        if(count($answer) > 0){
            return true;            
        }else{
            return false;
        }
    }

    public function emailExistsChange($email, $idUser){

        $mapper = function($row) {
            return true;
        };

        $sql = "SELECT * FROM usuario WHERE email=? and id <> ?;";

        $answer = $this->queryList($sql, [$email, $idUser], $mapper);

        if(count($answer) > 0){
            return true;            
        }else{
            return false;
        }
    }

    public function getIdUser($username){
        $sql = "SELECT id FROM usuario WHERE username=?;";
        $answer = $this->queryId($sql, [$username]);
        return $answer[0];
    }

    public function getUsername($id){
        $mapper = function($row){
            return $row['username'];
        };
        $sql = "SELECT username FROM usuario WHERE id=?;";
        $answer = $this->queryFirst($sql, [$id], $mapper);
        return $answer;
    }
}

<?php
//FALTA INCLUIR TODOS LOS MODELOS
include_once './model/ConfigurationRepository.php';
include_once './model/UserRepository.php';

class Validator{

    public static function validate($var){
        // Retorna true si el parametro esta seteado y es distinto de "".
        return isset($var) && $var != "";
    }
    
    public static function validateArray($array){
        foreach ($array as $valor){
            if(!self::validate($valor)){
                return false;
            }
        }
        return true;
    }
    
    public static function validateDate($date, $format = 'Y-m-d'){
        // Devuelve true si la fecha existe. Por defecto usa el formato YYYY - MM - DD .
        $d = DateTime::createFromFormat($format, $date);
        return $d->format($format) == $date;
    }
    
    public static function validateLogin(){
        // Devuelve true si hay un usuario logueado.
        self::starSession();
        return isset($_SESSION['username']) ? true : false;
    }

    public static function username(){
        self::starSession();
        return $_SESSION['username'];
    }

    public static function validatePasswords($p1, $p2){
        // Devuelve true si las contraseñas no son vacias y coinciden.
        return validate($p1) && validate($p2) && ($p1 == $p2);
    }

    public static function validateEmail($email){
        // Devuelve true si el email no esta en uso en la db, false caso contrario.
        return validate($email) && boolval(filter_var($email, FILTER_VALIDATE_EMAIL)) ;
    }

    public static function validateEmailLogin($email){
        return validate($email) && boolval(filter_var($email, FILTER_VALIDATE_EMAIL));
    }
    
    public static function starSession(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function navData(){
        self::starSession();
        return array(
            'logued' => self::validateLogin(), 
            'configurationPermission' => self::checkPermissions('configuration'), 
            'username' => isset($_SESSION['username'])? $_SESSION['username'] : "",
            'configuration' => ConfigurationRepository::getInstance()->listAll(),
            'isAdmin' => Validator::checkPermissions('usuario_destroy')
        );
    }

    public static function checkPermissions($action){
        if(self::validateLogin()){
            return UserRepository::getInstance()->canDo($_SESSION['username'], $action);            
        }else{
            return false;
        }
    }

    public static function validateUsername($username){
        return !UserRepository::getInstance()->usernameExists($username);
    }

    public static function validateUsernameChange($username, $idUser){
        return !UserRepository::getInstance()->usernameExistsChange($username, $idUser);
    }

    public static function validateEmailChange($email, $idUser){
        return !UserRepository::getInstance()->emailExistsChange($email, $idUser);
    }

    public static function paswordAuth($user, $pass) {
        return password_verify($pass, UserRepository::getInstance()->pass($user));
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
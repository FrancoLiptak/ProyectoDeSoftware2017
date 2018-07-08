<?php
include_once "TwigController.php";
include_once "validator.php";
include_once "model/UserRepository.php";
include_once "model/RoleRepository.php";
include_once 'IndexController.php';

Class ListUsersController{

    private static $instance;


    public function deleteUser(){
        if(Validator::checkPermissions('usuario_destroy')){
            UserRepository::getInstance()->deleteUser($_POST['deleteId']);
            $this->basicView();
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function basicView(){
        if(Validator::checkPermissions('usuario_index')){              
            $array = Validator::navData() + array( 'isAdmin' => Validator::checkPermissions('usuario_destroy')) + array( 'users' => $this->listUsers()) + array('roles' => RoleRepository::getInstance()->listAll());
            echo TwigController::getTwig()->render('listUsers.html.twig', $array);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    private function listUsers(){
        return UserRepository::getInstance()->listAll();
    }

    public function editUser(){
        if(Validator::checkPermissions('usuario_update')){
            $editPass = false;
            $editActivo = false;

            $dataUser = array(
                "last_name" => $_POST['editLastName'],
                "name" => $_POST['editName'],
                "email" => $_POST['editEmail'],
                "user_name" => $_POST['editUserName'],
            );

            if(strlen($_POST['editPassword']) > 0){ // Si no se quiso resetar la contraseña del usuario, no se actualiza.
                $dataUser = array_merge($dataUser, ["password" => Validator::hashPassword($_POST['editPassword'])]);
                $editPass = true;
            }

            if( ( $_POST['editId'] ) != ( $_SESSION['userId'] ) ){ // El usuario no puede desactivarse a sí mismo. Por ende, no va a verlo en el formulario. Pero sí puede desactivar a otros.
                $dataUser = array_merge($dataUser, ["activo" => $_POST['editActivo']]);
                $editActivo = true;
            }

            if(Validator::validateArray($dataUser) && Validator::validateArray($_POST['roles'])){
                
                if (!preg_match("/^[[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}]*$/",$dataUser['name'])) {
                    $this->errorView('El nombre debe contener solo letras y espacios en blanco');                        
                }else{
                    if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,64}]*$/",$dataUser['last_name'])) {
                        $this->errorView('El apellido debe contener solo letras y espacios en blanco');                        
                    }else{
                        if (!filter_var($dataUser['email'], FILTER_VALIDATE_EMAIL)) {
                            $this->errorView('Formato de email inválido');
                        }else{
                            if (!Validator::validateUsernameChange($dataUser['user_name'], $_POST['editId'])){
                                $this->errorView('El nombre de usuario ya existe');                                                
                            }else{
                                if(!Validator::validateEmailChange($dataUser['email'], $_POST['editId'])){
                                    $this->errorView('El email ya existe');
                                }else{
                                    date_default_timezone_set('UTC');
                                    $date = date("c");
                                    $dataUser = array_merge(
                                        $dataUser, [
                                        "updated_at" => $date,
                                        ]
                                    );

                                    UserRepository::getInstance()->editUser($dataUser, $_POST['roles'], $_POST['editId'], $editPass, $editActivo);
                                    
                                    if($_POST['editId'] == $_SESSION['userId']){ // Si modifiqué el nombre de usuario que está logueado actualmente, lo actualizo en SESSION.
                                        $_SESSION['username'] = $dataUser['user_name'];
                                    }
                                    
                                    $this->basicView();
                                }
                            }
                        }
                    }
                }
            }
        }else{
            IndexController::getInstance()->basicView();
        }
    }
    
    public function create(){
        if(Validator::checkPermissions('usuario_new')){
            $array = Validator::navData() + array('isAdmin' => Validator::checkPermissions('paciente_destroy')) + array( 'roles' => RoleRepository::getInstance()->listAll());
            echo TwigController::getTwig()->render('register.html.twig', $array);
        }else{
            IndexController::getInstance()->basicView();
        }
    }

    public function errorView($message){
        echo TwigController::getTwig()->render(
            'listUsers.html.twig',
            Validator::navData() + ['roles' => RoleRepository::getInstance()->listAll()] + ['errorMessage' => $message]);    
    }

    public function createUser(){
        if(Validator::checkPermissions('usuario_new')){
            $dataUser = array(
                ":first_name" => $_POST['first-name'],
                ":last_name" => $_POST['last-name'],
                ":username" => $_POST['username'],
                ":email" => $_POST['email']
            );
        
            if(Validator::validateArray($dataUser) && Validator::validateArray($_POST['roles'])){
                if($_POST['password'] === $_POST['confirm-password']){
                    $dataUser = $dataUser + 
                        [":password" => Validator::hashPassword($_POST['password'])];
                    if (!preg_match("/^[[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}]*$/",$dataUser[':first_name'])) {
                    $this->errorView('El nombre debe contener solo letras y espacios en blanco');                        
                    }else{
                        if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,64}]*$/",$dataUser[':last_name'])) {
                            $this->errorView('El apellido debe contener solo letras y espacios en blanco');                        
                        }else{
                            if (!Validator::validateUsername($dataUser[':username'])) {
                                $this->errorView('El nombre de usuario ya existe');                                                
                            }else{
                                if (! filter_var($dataUser[':email'], FILTER_VALIDATE_EMAIL)) {
                                    echo $dataArray[':email'];
                                    $this->errorView('Formato de email invalido');
                                }else{
                                    date_default_timezone_set('UTC');
                                    $date = date("c");
                                    $dataUser = array_merge(
                                        $dataUser, [
                                        ":created_at" => $date,
                                        ":updated_at" => $date,
                                        ":activo" => 1,
                                        ":borrado" => 0,    
                                        ]
                                    );
                                    UserRepository::getInstance()->insert($dataUser, $_POST['roles']);
                                    $this->basicView();
                                }
                            }
                        }    
                    }
                }else{
                    $this->errorView('Fallo al confirmar contraseña');
                }
            }else{
                $this->errorView('Debe completar todos los campos');
            }
        }else{
            IndexController::getInstance()->basicView();
        }
    }

}
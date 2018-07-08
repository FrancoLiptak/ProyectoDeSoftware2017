<?php

class HealthControl{
    private $id;
    private $edad;
    private $fecha;
    private $pesa;
    private $vacunas_completas;
    private $vacunas_observaciones;
    private $maduracion_acorde;
    private $maduracion_observaciones;
    private $ex_fisico_normal;
    private $ex_fisico_observaciones;
    private $pc;
    private $ppc;
    private $talla;
    private $alimentacion;
    private $observaciones_generales;
    private $paciente_id;
    private $usuario;

    public function __construct($id, $edad, $fecha, $pesa, $vacunas_completas, $vacunas_observaciones, $maduracion_acorde, $maduracion_observaciones, $ex_fisico_normal, $ex_fisico_observaciones, $pc, $ppc, $talla, $alimentacion, $observaciones_generales, $paciente_id, $usuario){
        $this->edad = $edad;
        $this->fecha = $fecha;
        $this->id = $id;
        $this->pesa = $pesa;
        $this->vacunas_completas = $vacunas_completas;
        $this->vacunas_observaciones = $vacunas_observaciones;
        $this->maduracion_acorde = $maduracion_acorde;
        $this->maduracion_observaciones = $maduracion_observaciones;
        $this->ex_fisico_normal = $ex_fisico_normal;
        $this->ex_fisico_observaciones = $ex_fisico_observaciones;
        $this->pc = $pc;
        $this->ppc = $ppc;
        $this->talla = $talla;
        $this->alimentacion = $alimentacion;
        $this->observaciones_generales = $observaciones_generales;
        $this->paciente_id = $paciente_id;
        $this->usuario = $usuario;
    }

    //Setters

    public function setId($id){
        $this->id = $id;
    }

    public function setEdad($edad){
        $this->edad = $edad;
    }

    public function setFecha($fecha){
        $this->fecha = $fecha;
    }

    public function setPesa($pesa){
        $this->pesa = $pesa;
    }

    public function setVacunas_completas($vacunas_completas){
        $this->vacunas_completas = $vacunas_completas;
    }

    public function setvacunas_observaciones($vacunas_observaciones){
        $this->vacunas_observaciones = $vacunas_observaciones;
    }

    public function setmaduracion_acorde($maduracion_acorde){
        $this->maduracion_acorde = $maduracion_acorde;
    }

    public function setmaduracion_observaciones($maduracion_observaciones){
        $this->maduracion_observaciones = $maduracion_observaciones;
    }

    public function setex_fisico_normal($ex_fisico_normal){
        $this->ex_fisico_normal = $ex_fisico_normal;
    }

    public function setex_fisico_observaciones($ex_fisico_observaciones){
        $this->ex_fisico_observaciones = $ex_fisico_observaciones;
    }

    public function setpc($pc){
        $this->pc = $pc;
    }

    public function setppc($ppc){
        $this->ppc = $ppc;
    }

    public function settalla($talla){
        $this->talla = $talla;
    }

    public function setalimentacion($alimentacion){
        $this->alimentacion = $alimentacion;
    }

    public function setobservaciones_generales($observaciones_generales){
        $this->observaciones_generales = $observaciones_generales;
    }

    public function setpaciente_id($paciente_id){
        $this->paciente_id = $paciente_id;
    }

    public function setusuario($usuario){
        $this->usuario = $usuario;
    }

    //Getters

    public function getId() {
        return $this->id;
    }

    public function getEdad() {
        return $this->edad;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getPesa() {
        return $this->pesa;
    }

    public function getvacunas_completas(){
        return $this->vacunas_completas;
    }

    public function getvacunas_observaciones(){
        return $this->vacunas_observaciones;
    }

    public function getmaduracion_acorde(){
        return $this->maduracion_acorde;
    }

    public function getmaduracion_observaciones(){
        return $this->maduracion_observaciones;
    }

    public function getex_fisico_normal(){
        return $this->ex_fisico_normal;
    }

    public function getex_fisico_observaciones(){
        return $this->ex_fisico_observaciones;
    }

    public function getpc(){
        return $this->pc;
    }

    public function getppc(){
        return $this->ppc;
    }

    public function gettalla(){
        return $this->talla;
    }

    public function getalimentacion(){
        return $this->alimentacion;
    }

    public function getobservaciones_generales(){
        return $this->observaciones_generales;
    }

    public function getpaciente_id(){
        return $this->paciente_id;
    }

    public function getusuario(){
        return $this->usuario;
    }
}
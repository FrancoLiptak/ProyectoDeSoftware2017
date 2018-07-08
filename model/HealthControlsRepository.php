<?php
include_once "PDORepository.php";
include_once "HealthControl.php";
include_once "PatientRepository.php";

class HealthControlsRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }

    public function listAllFrom($idPatient) {
 
        $mapper = function($row) {
            $fec_nac = PatientRepository::getInstance()->getBirthdate($row['paciente_id']);
            date_default_timezone_set('UTC');
            $edad = (strtotime($row['fecha']) - strtotime($fec_nac));
            $username = UserRepository::getInstance()->getUsername($row['user_id']);
            $resource = new HealthControl($row['id'], $edad, $row['fecha'], $row['pesa'], $row['vacunas_completas'], $row['vacunas_observaciones'], $row['maduracion_acorde'], $row['maduracion_observaciones'], $row['ex_fisico_normal'], $row['ex_fisico_observaciones'], $row['pc'], $row['ppc'], $row['talla'], $row['alimentacion'], $row['observaciones_generales'], $row['paciente_id'], $username);
            return $resource;
        };

        $answer = $this->queryList(
                "SELECT * FROM control_salud WHERE paciente_id = ? ORDER BY fecha", [$idPatient], $mapper);

        return $answer;
    }

    public function deleteControlSalud($id) {

        $this->executeQuery(
            "DELETE FROM control_salud WHERE id=?",[$id]
        );

    }

    public function editControlSalud($data) {

        $sql = "UPDATE control_salud
        SET fecha = :fecha,
            pesa = :pesa,
            vacunas_completas = :vacunas_completas,
            vacunas_observaciones = :vacunas_observaciones,
            maduracion_acorde = :maduracion_acorde,
            maduracion_observaciones = :maduracion_observaciones,
            ex_fisico_normal = :ex_fisico_normal,
            ex_fisico_observaciones = :ex_fisico_observaciones,
            pc = :pc,
            ppc = :ppc,
            talla = :talla,
            alimentacion = :alimentacion,
            observaciones_generales = :observaciones_generales
        WHERE id = :id;";

        $this->executeQuery($sql, $data);

    }

    public function createControlSalud($data) {
        
        $sql = "INSERT INTO control_salud (fecha, pesa, vacunas_completas, vacunas_observaciones, maduracion_acorde, maduracion_observaciones, ex_fisico_normal, ex_fisico_observaciones, pc, ppc, talla, alimentacion, observaciones_generales, paciente_id, user_id)
        VALUES (:fecha, :pesa, :vacunas_completas, :vacunas_observaciones, :maduracion_acorde, :maduracion_observaciones, :ex_fisico_normal, :ex_fisico_observaciones, :pc, :ppc, :talla, :alimentacion, :observaciones_generales,  :paciente_id, :user_id);";

        $this->executeQuery($sql, $data);
        
    }

}
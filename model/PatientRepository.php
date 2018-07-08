<?php
include_once "PDORepository.php";
include_once "Patient.php";
include_once "Resource.php";
include_once "DataDemographic.php";
include_once "AverageDemographic.php";

class PatientRepository extends PDORepository {
    
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

        $sql = "SELECT id FROM paciente WHERE borrado=?;";

        $idPatients = $this->getInformation($sql, ['0']);

        $patients = [];

        foreach ($idPatients as &$id) {
            array_push($patients, $this->getPatient($id[0]));
        }
        
        return $patients;
    }

    public function insert($patientArray, $dataDemographicArray) {
        
        $sql = "INSERT INTO datos_demograficos (heladera, electricidad, mascota, tipo_vivienda_id, tipo_calefaccion_id, tipo_agua_id)
                VALUES (:fridge, :electricity, :pet, :type_of_housing, :type_of_heating, :type_of_water);";

        $lastId;
        $result = $this->insertAndGetLastId($sql, $dataDemographicArray, $lastId); // Obtengo el ID de los datos demográficos insertados.

        if ($result = true){
            
            // Guardo el nuevo paciente
    
            $sql = "INSERT INTO paciente (apellido, nombre, domicilio, tel, fec_nac, genero, obra_social_id, tipo_doc_id, numero, datos_demograficos_id)
                    VALUES (:last_name, :first_name, :address, :phone, :birthday, :gender, :health_insurance, :document_type, :document_number, $lastId);";
    
            return $this->executeQuery($sql, $patientArray);

        }else{

            return false;

        }

    }

    public function deletePatient($id){

        $sql =  "UPDATE paciente
                SET borrado = 1
                WHERE id = ?";
        
        $this->executeQuery($sql, [$id]);

    }


    public function editPatient($patientArray, $dataDemographicArray, $idPatient) {

       $sql =   "UPDATE datos_demograficos
                SET electricidad = :electricity,
                    heladera = :fridge,
                    mascota = :pet,
                    tipo_vivienda_id = :type_of_housing,
                    tipo_calefaccion_id = :type_of_heating,
                    tipo_agua_id = :type_of_water
                WHERE id = (SELECT datos_demograficos_id FROM paciente WHERE id = '$idPatient')";
        
        $result = $this->executeQuery($sql, $dataDemographicArray);

        if ( $result = true ){
            
            $sql = "UPDATE paciente 
            SET  apellido = :last_name,
                 nombre = :first_name, 
                 domicilio = :address,
                 tel = :phone,
                 fec_nac = :birthday,
                 genero = :gender,
                 obra_social_id = :health_insurance,
                 tipo_doc_id = :document_type,
                 numero = :document_number
            WHERE id = '$idPatient'";
    
            return $this->executeQuery($sql, $patientArray);

        }else{

            return false;

        }
        
    }

    public function getPatient($idPatient){

        $dataDemographic; $patient;

        $this->getBasicPatient($idPatient, $dataDemographic, $patient);

        $objectPatient = $this->createPatientWithResources($dataDemographic, $patient);

        return $objectPatient;

    }

    private function createPatientWithResources($dataDemographic, $patient){ // Pido recursos y creo objetos

        $typeDoc = $patient['tipo_doc_id'];
        $typeDoc = Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-documento/$typeDoc");

        $healtInsurrance = $patient['obra_social_id'];
        $healtInsurrance = Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/obra-social/$healtInsurrance");

        $typeOfWater = $dataDemographic['tipo_agua_id'];
        $typeOfWater = Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua/$typeOfWater");

        $typeOfHousing = $dataDemographic['tipo_vivienda_id'];
        $typeOfHousing = Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-vivienda/$typeOfHousing");
        
        $typeOfHeating = $dataDemographic['tipo_calefaccion_id'];
        $typeOfHeating = Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-calefaccion/$typeOfHeating");

        $objectDataDemographic = new DataDemographic($dataDemographic['id'], $dataDemographic['electricidad'], $dataDemographic['heladera'], $dataDemographic['mascota'], $typeOfHousing, $typeOfHeating, $typeOfWater);
        $objectPatient = new Patient($patient['id'], $patient['apellido'], $patient['nombre'], $patient['domicilio'], $patient['tel'], $patient['fec_nac'], $patient['genero'], $healtInsurrance, $typeDoc, $patient['numero'], $objectDataDemographic);

        return $objectPatient;
    }

    private function getBasicPatient($idPatient, &$dataDemographic, &$patient){ // Pacientes con IDS en cosas como "tipo de agua"

        $sql = "SELECT * FROM datos_demograficos WHERE id = (SELECT datos_demograficos_id FROM paciente WHERE id=?);";

        $dataDemographic = $this->getInformation($sql, [$idPatient]);

        $dataDemographic = $dataDemographic[0];

        $sql = "SELECT * FROM paciente WHERE id=?;";

        $patient = $this->getInformation($sql, [$idPatient]);

        $patient = $patient[0];

    }

    public function getBirthdate($idPatient) {
        
        $mapper = function($row) {
            return $row['fec_nac'];
        };
        
        $answer = $this->queryFirst(
            "SELECT fec_nac FROM paciente WHERE id=?;", [$idPatient], $mapper);

        return $answer;
    }

    public function getName($idPatient) {
        
        $mapper = function($row) {
            return $row['apellido'].' '.$row['nombre'];
        };
        
        $answer = $this->queryFirst(
            "SELECT apellido, nombre FROM paciente WHERE id=?;", [$idPatient], $mapper);

        return $answer;
    }

    public function getGender($idPatient) {
        
        $mapper = function($row) {
            return $row['genero'];
        };
        
        $answer = $this->queryFirst(
            "SELECT genero FROM paciente WHERE id=?;", [$idPatient], $mapper);

        return $answer;
    }

    public function averageDemographicList(){

        $fridge = [];
        $electricity = [];
        $pet = [];
        $livingPlace = [];
        $heating = [];
        $water = [];

        $mapper = function ($row) use (&$fridge, &$electricity, &$pet, &$livingPlace, &$heating, &$water) {
            $fridge[] = $row['heladera'];
            $electricity[] = $row['electricidad'];
            $pet[] = $row['mascota'];
            $livingPlace[] = $row['tipo_vivienda_id'];
            $water[] = $row['tipo_agua_id'];
            $heating[] = $row['tipo_calefaccion_id'];
        };

        $sql="SELECT datos_demograficos.*
        FROM datos_demograficos INNER JOIN paciente ON (paciente.datos_demograficos_id = datos_demograficos.id);";

        $this->queryMap($sql, [], $mapper);

        $answer = [
            'averageElectricity' => [], 
            'averageFridge' => [],
            'averageLivingPlace' => [],
            'averageHeating' => [], 
            'averageWater' => [],
            'averagePet' => [],
        ];

        $answer['averageElectricity'] = $this->averageArray($electricity);
        $answer['averageFridge'] = $this->averageArray($fridge);
        $answer['averageLivingPlace'] = $this->averageArray($livingPlace, 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-vivienda/');
        $answer['averageHeating'] = $this->averageArray($heating, 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-calefaccion/');
        $answer['averageWater'] = $this->averageArray($water, 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua/');
        $answer['averagePet'] = $this->averageArray($pet);

        return $answer;
        /*
        $sqlFridges="SELECT heladera, count(heladera) as cant
        FROM datos_demograficos INNER JOIN paciente ON (paciente.datos_demograficos_id = datos_demograficos.id)
        GROUP BY heladera;";

        $sqlElectricity="SELECT electricidad, count(electricidad) as cant
        FROM datos_demograficos INNER JOIN paciente ON (paciente.datos_demograficos_id = datos_demograficos.id)
        GROUP BY electricidad;";

        $sqlPet="SELECT mascota, count(mascota) as cant
        FROM datos_demograficos INNER JOIN paciente ON (paciente.datos_demograficos_id = datos_demograficos.id)
        GROUP BY mascota;";

        $sqlLivingPlace="SELECT tipo_vivienda_id, count(tipo_vivienda_id) as cant
        FROM datos_demograficos INNER JOIN paciente ON (paciente.datos_demograficos_id = datos_demograficos.id)
        GROUP BY tipo_vivienda_id;";

        $sqlHeating="SELECT tipo_calefaccion_id, count(tipo_calefaccion_id) as cant
        FROM datos_demograficos INNER JOIN paciente ON (paciente.datos_demograficos_id = datos_demograficos.id)
        GROUP BY tipo_calefaccion_id;";

        $sqlWater="SELECT tipo_agua_id, count(tipo_agua_id) as cant
        FROM datos_demograficos INNER JOIN paciente ON (paciente.datos_demograficos_id = datos_demograficos.id)
        GROUP BY tipo_agua_id;";
*/
   }

    private function averageArray($dataArray, $apiUrl=null){
        $answer = [];
        $count = array_count_values($dataArray);
        if(isset($apiUrl)){
            foreach ($count as $key => $value) {
                $answer[] = new AverageDemographic(
                    Resource::getResource($apiUrl.$key)['nombre'], 
                    $value/count($dataArray)*100);
            }    
        }else{
            foreach ($count as $key => $value) {
                $answer[] = new AverageDemographic(($key == 1)? 'Si':'No', $value/count($dataArray)*100);
            }    
        }
        return $answer;
    }
}
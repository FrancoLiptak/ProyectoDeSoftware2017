<?php
include_once "PDORepository.php";
include_once "model/PatientRepository.php";
include_once "Promedio.php";

class PromedioRepository extends PDORepository {
    
    private static $instance;
    private $urlGrowthMale = 'http://www.who.int/childgrowth/standards/tab_wfa_boys_p_0_13.txt';
    private $urlGrowthFemale = 'http://www.who.int/childgrowth/standards/wfa_girls_0_13_zscores.txt';
    private $urlHeightMale = 'http://www.who.int/childgrowth/standards/tab_lhfa_boys_p_0_2.txt';
    private $urlHeightFemale = 'http://www.who.int/childgrowth/standards/tab_lhfa_girls_p_0_2.txt';
    private $urlPpcMale = 'http://www.who.int/childgrowth/standards/second_set/tab_hcfa_boys_p_0_13.txt';
    private $urlPpcFemale = 'http://www.who.int/childgrowth/standards/second_set/tab_hcfa_girls_p_0_13.txt';            

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }

    private function getList($file, $mapper){
        $list = [];
        foreach ($file as $row) {
            $rowArray = explode("	", $row);
            $list[] = $mapper($rowArray);
        }
        return $list;
        
    }

    public function listAll() {

        $maleGrowthMapper = function($rowArray) {
            $resource = new Promedio($rowArray[0], $rowArray[6], $rowArray[9], $rowArray[11], $rowArray[13], $rowArray[16]);
            return $resource;            
        };

        $femaleGrowthMapper = function($rowArray) {
            $resource = new Promedio($rowArray[0], $rowArray[5], $rowArray[6], $rowArray[7], $rowArray[8], $rowArray[9]);
            return $resource;            
        };

        $heightOrPpcMapper = function($rowArray) {
            $resource = new Promedio($rowArray[0], $rowArray[7], $rowArray[10], $rowArray[12], $rowArray[14], $rowArray[17]);
            return $resource;            
        };

        $gender = PatientRepository::getInstance()->getGender($_GET['idPatient']);
        if ($gender == 'Masculino'){
            $growth = file($this->urlGrowthMale);
            array_shift($growth);
            $height = file($this->urlHeightMale);
            array_shift($height);
            $ppc = file($this->urlPpcMale);
            array_shift($ppc);
            
            $answer = [
                'averageGrowth' => $this->getList($growth, $maleGrowthMapper),
                'averageHeight' => $this->getList($height, $heightOrPpcMapper),
                'averagePpc' => $this->getList($ppc, $heightOrPpcMapper),
            ];

        }else{
            $growth = file($this->urlGrowthFemale);
            array_shift($growth);
            $height = file($this->urlHeightFemale);
            array_shift($height);
            $ppc = file($this->urlPpcFemale);
            array_shift($ppc);
            
            $answer = [
                'averageGrowth' => $this->getList($growth, $femaleGrowthMapper),
                'averageHeight' => $this->getList($height, $heightOrPpcMapper),
                'averagePpc' => $this->getList($ppc, $heightOrPpcMapper),
            ];
        }

        return $answer;
    }

}
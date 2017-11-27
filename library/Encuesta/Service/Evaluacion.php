<?php
class Encuesta_Service_Evaluacion {
    
    private $evaluacionDAO;
    
    public function __construct() {
        
        $this->evaluacionDAO = new Encuesta_Data_DAO_Evaluacion();
    }
    
    public function getEvaluacionById($idEval) {
        ;
    }
}
<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Pregunta {
    
    private $tablePregunta;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePregunta = new Encuesta_Data_DbTable_Pregunta($config);
    }
}
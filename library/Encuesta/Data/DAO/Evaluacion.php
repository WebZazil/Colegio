<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Evaluacion {
    
    private $tableEvaluacion;
    private $tableEncuesta;
    
    public function __construct($adapter) {
        $config = array('db' => $adapter);
        
        $this->tableEvaluacion = new Encuesta_Data_DbTable_Evaluacion($config);
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        
    }
    
    public function getEvaluacionById($idEvaluacion) {
        $tE = $this->tableEvaluacion;
        $select = $tE->select()->from($tE)->where($idEvaluacion);
        $rowE = $tE->fetchRow($select);
        
        return $rowE->toArray();
    }
    
    public function getEncuestaById($idEncuesta) {
        $tE = $this->tableEncuesta;
        $select = $tE->select()->from($tE)->where('idEncuesta=?',$idEncuesta);
        $rowEncuesta = $tE->fetchRow($select);
        
        return $rowEncuesta->toArray();
    }
    
    
    
    public function getAllEvaluaciones() {
        return $this->tableEvaluacion->fetchAll()->toArray();
    }
    
    
}
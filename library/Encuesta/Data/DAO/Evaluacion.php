<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Evaluacion {
    
    private $tableEvaluacion;
    private $tableEncuesta;
    private $tableConjuntoEvaluador;
    private $tableEvaluador;
    
    public function __construct($adapter) {
        $config = array('db' => $adapter);
        
        $this->tableEvaluacion = new Encuesta_Data_DbTable_Evaluacion($config);
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableConjuntoEvaluador = new Encuesta_Data_DbTable_ConjuntoEvaluador($config);
        $this->tableEvaluador = new Encuesta_Data_DbTable_Evaluador($config);
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
    
    
    public function getEvaluadoresConjunto($idConjunto) {
        $tCE = $this->tableConjuntoEvaluador;
        $select = $tCE->select()->from($tCE)->where('idConjunto=?',$idConjunto);
        $rowConjunto = $tCE->fetchAll($select);
        
        $idsEvaluadores = explode(',', $rowConjunto->idsEvaluadores);
        $tE = $this->tableEvaluador;
        $select = $tE->select()->from($tE)->where('idEvaluador IN (?)',$idsEvaluadores);
        $rowsEvaluadores = $tE->fetchAll($select);
        print_r($rowsEvaluadores);
        
        
    }
    
    
}
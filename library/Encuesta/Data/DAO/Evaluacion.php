<?php
/**
 * Revisado Febrero 2018 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Evaluacion {
    
    private $tableEvaluacionConjunto;
    private $tableEncuesta;
    private $tableConjuntoEvaluador;
    private $tableEvaluador;
    
    public function __construct($adapter) {
        $config = array('db' => $adapter);
        
        $this->tableEvaluacionConjunto = new Encuesta_Data_DbTable_EvaluacionConjunto($config);
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableConjuntoEvaluador = new Encuesta_Data_DbTable_ConjuntoEvaluador($config);
        $this->tableEvaluador = new Encuesta_Data_DbTable_Evaluador($config);
    }
    
    public function getEvaluacionById($idEvaluacion) {
        $tEC = $this->tableEvaluacionConjunto;
        $select = $tEC->select()->from($tEC)->where('idEvaluacionConjunto=?', $idEvaluacion);
        $rowE = $tEC->fetchRow($select);
        
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
        $select = $tCE->select()->from($tCE)->where('idConjuntoEvaluador=?',$idConjunto);
        $rowConjunto = $tCE->fetchRow($select);
        
        $idsEvaluadores = explode(',', $rowConjunto->idsEvaluadores);
        $tE = $this->tableEvaluador;
        $select = $tE->select()->from($tE)->where('idEvaluador IN (?)',$idsEvaluadores);
        $rowsEvaluadores = $tE->fetchAll($select);
        
        return $rowsEvaluadores->toArray();
    }
    
    public function getEvaluadoresGrupo($idGrupo) {
        $tCE = $this->tableConjuntoEvaluador;
        $select = $tCE->select()->from($tCE)->where('idGrupoEscolar=?',$idGrupo);
        $rowsCE = $tCE->fetchAll($select)->toArray();
        
        $idsEvals = array();
        
        foreach ($rowsCE as $rowCE){
            $ids = explode(',', $rowCE['idsEvaluadores']);
            foreach ($ids as $id){
                if($id != ''){
                    $idsEvals[] = $ids;
                }
            }
        }
        
        $tE = $this->tableEvaluador;
        $select = $tE->select()->from($tE)->where('idEvaluador IN (?)',$idsEvals);
        $rowE = $tE->fetchAll($select);
        
        return $rowE->toArray();
    }
    
    public function getEvaluadorById($idEvaluador) {
       $tE = $this->tableEvaluador;
       $select = $tE->select()->from($tE)->where('idEvaluador=?',$idEvaluador);
       $rowEvaluador = $tE->fetchRow($select);
       
       return $rowEvaluador->toArray();
    }
    
    
}
<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_ConjuntoEvaluador {
    
    private $tableConjuntoEvaluador;
    private $tableEvaluador;
    private $tableGrupoEscolar;
    private $tableEvaluacionConjunto;
    private $tableEncuesta;
    private $tableAsignacionGrupo;
    
   
    public function __construct($dbAdapter){
        $config = array('db' => $dbAdapter);
        
        $this->tableConjuntoEvaluador = new Encuesta_Data_DbTable_ConjuntoEvaluador($config);
        $this->tableEvaluador = new Encuesta_Data_DbTable_Evaluador($config);
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tableEvaluacionConjunto = new Encuesta_Data_DbTable_EvaluacionConjunto($config);
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
    }
    
    public function getAllRowsConjuntosByIdGrupoEscolar($idGrupoEscolar) {
        $tCE = $this->tableConjuntoEvaluador;
        $select = $tCE->select()->from($tCE)->where('idGrupoEscolar=?',$idGrupoEscolar);
        $rowsGE = $tCE->fetchAll($select);
        
        return $rowsGE->toArray();
    }
    
    public function getRowConjuntoById($idConjunto) {
        $tCE = $this->tableConjuntoEvaluador;
        $select = $tCE->select()->from($tCE)->where('idConjuntoEvaluador=?',$idConjunto);
        $rowCE = $tCE->fetchRow($select);
        
        return $rowCE->toArray();
    }
    
    public function getObjectConjuntoById($idConjunto) {
        $obj = array();
        
        $rowCE = $this->getRowConjuntoById($idConjunto);
        $obj['conjunto'] = $rowCE;
        
        $tGE = $this->tableGrupoEscolar;
        $select = $tGE->select()->from($tGE)->where('idGrupoEscolar=?',$rowCE['idGrupoEscolar']);
        $rowGE = $tGE->fetchRow($select)->toArray();
        $obj['grupo'] = $rowGE;
        
        $tE = $this->tableEvaluador;
        $select = $tE->select()->from($tE)->where('idEvaluador IN (?)',explode(',', $rowCE['idsEvaluadores']));
        $rowE = $tE->fetchAll($select)->toArray();
        $obj['evaluadores'] = $rowE;
        
        //$obj['evaluaciones'] = array();
        
        $tEC = $this->tableEvaluacionConjunto;
        $select = $tEC->select()->from($tEC)->where('idConjuntoEvaluador=?',$rowCE['idConjuntoEvaluador']);
        $rowsEC = $tEC->fetchAll($select)->toArray();
        
        if (!empty($rowsEC)) {
            $tEn = $this->tableEncuesta;
            foreach ($rowsEC as $rowEC){
                $select = $tEn->select()->from($tEn)->where('idEncuesta=?',$rowEC['idEvaluacion']);
                $rowEvC = $tEn->fetchRow($select)->toArray();
                
                $obj['evaluaciones'][] = $rowEvC;
            };
        }else{
            $obj['evaluaciones'] = array();
        }
        
        return $obj;
    }
    
    public function getAsignacionesByIdConjuntoAndIdEvaluacion($idConjunto,$idEvaluacion) {
        $tEC = $this->tableEvaluacionConjunto;
        $select = $tEC->select()->from($tEC)
            ->where('idConjuntoEvaluador=?',$idConjunto)
            ->where('idEvaluacion=?',$idEvaluacion);
        $rowEC = $tEC->fetchRow($select)->toArray();
        
        $idsAsignaciones = explode(',', $rowEC['idsAsignacionesGrupo']);
        
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idAsignacionGrupo IN (?)', $idsAsignaciones);
        $rowsAsignaciones = $tAG->fetchAll($select);
        
        return $rowsAsignaciones->toArray();
    }
    
    
}
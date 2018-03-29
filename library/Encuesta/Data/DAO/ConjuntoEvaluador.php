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
    
    private $tableCicloEscolar;
   
    public function __construct($dbAdapter){
        $config = array('db' => $dbAdapter);
        
        $this->tableConjuntoEvaluador = new Encuesta_Data_DbTable_ConjuntoEvaluador($config);
        $this->tableEvaluador = new Encuesta_Data_DbTable_Evaluador($config);
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tableEvaluacionConjunto = new Encuesta_Data_DbTable_EvaluacionConjunto($config);
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        
        $this->tableCicloEscolar = new Encuesta_Data_DbTable_CicloEscolar($config);
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
                $select = $tEn->select()->from($tEn)->where('idEncuesta=?',$rowEC['idEncuesta']);
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
            ->where('idEncuesta=?',$idEvaluacion);
        $rowEC = $tEC->fetchRow($select)->toArray();
        
        $idsAsignaciones = explode(',', $rowEC['idsAsignacionesGrupo']);
        
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idAsignacionGrupo IN (?)', $idsAsignaciones);
        $rowsAsignaciones = $tAG->fetchAll($select);
        
        return $rowsAsignaciones->toArray();
    }
    
    public function getAllConjuntosByIdCicloEscolar($idCicloEscolar) {
        $tGE = $this->tableGrupoEscolar;
        $select = $tGE->select()->from($tGE)->where('idCicloEscolar=?',$idCicloEscolar);
        $rowsGE = $tGE->fetchAll($select)->toArray();
        $idsGrupos = array();
        
        foreach ($rowsGE as $rowGE) {
            $idsGrupos[] = $rowGE['idGrupoEscolar'];
        }
        
        $tCE = $this->tableConjuntoEvaluador;
        $select = $tCE->select()->from($tCE)->where('idGrupoEscolar IN (?)', $idsGrupos);
        $rowsCE = $tCE->fetchAll($select);
        
        return $rowsCE->toArray();
    }
    
    public function deleteAsignacionConjunto($idConjunto,$idAsignacion,$idEncuesta) {
        $tEC = $this->tableEvaluacionConjunto;
        $select = $tEC->select()->from($tEC)->where('idConjuntoEvaluador=?',$idConjunto)->where('idEncuesta=?',$idEncuesta);
        $rowEC = $tEC->fetchRow($select);
        
        $idsAsignaciones = explode(',', $rowEC->idsAsignacionesGrupo);
        $id = array_search($idAsignacion, $idsAsignaciones);
        //unset( array_search('', $idsAsignaciones) );
        unset($idsAsignaciones[$id]);
        
        $rowEC->idsAsignacionesGrupo = implode(',', $idsAsignaciones);
        $rowEC->save();
    }
}
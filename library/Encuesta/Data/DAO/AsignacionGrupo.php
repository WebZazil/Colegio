<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_AsignacionGrupo {
    
    private $tableAsignacionGrupo;
    
    private $tableMateriaEscolar;
    private $tableDocente;
    private $tableGrupoEscolar;
    private $tableRegistro;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        $this->tableMateriaEscolar = new Encuesta_Data_DbTable_MateriaEscolar($config);
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tableDocente = new Encuesta_Data_DbTable_Docente($config);
        $this->tableRegistro = new Encuesta_Data_DbTable_Registro($config);
    }
    
    /**
     * 
     * @param int $idAsignacion
     */
    public function getObjByIdAsignacion($idAsignacion) {
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idAsignacionGrupo=?',$idAsignacion);
        $rowAsignacion = $tAG->fetchRow($select);
        
        $tME = $this->tableMateriaEscolar;
        $select = $tME->select()->from($tME)->where('idMateriaEscolar=?',$rowAsignacion->idMateriaEscolar);
        $rowME = $tME->fetchRow($select);
        
        $tGE = $this->tableGrupoEscolar;
        $select = $tGE->select()->from($tGE)->where('idGrupoEscolar=?',$rowAsignacion->idGrupoEscolar);
        $rowGE = $tGE->fetchRow($select);
        
        $tR = $this->tableRegistro;
        $select = $tR->select()->from($tR)->where('idRegistro=?',$rowAsignacion->idRegistro);
        $rowR = $tR->fetchRow($select);
        
        $object = array();
        $object['as'] = $rowAsignacion->toArray();
        $object['me'] = $rowME->toArray();
        $object['ge'] = $rowGE->toArray();
        $object['re'] = $rowR->toArray();
        
        return $object;
    }
    
    public function getDocenteByIdAsignacion($idAsignacion) {
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idAsignacionGrupo=?',$idAsignacion);
        $rowAG = $tAG->fetchRow($select)->toArray();
        
        $tR = $this->tableRegistro;
        $select = $tR->select()->from($tR)->where('idRegistro=?', $rowAG['idRegistro']);
        $rowR = $tR->fetchRow($select);
        
        return $rowR->toArray();
    }
    
    public function getAsignacionesGrupoByIdGrupo($idGrupoEscolar) {
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idGrupoEscolar=?',$idGrupoEscolar);
        $rowsAG = $tAG->fetchAll($select);
        
        return $rowsAG->toArray();
    }
    
    function getAsignacionByParams($params) {
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG);
        foreach ($params as $k => $v){
            $select->where($k.'=?',$v);
        }
        $rowAG = $tAG->fetchRow($select);
        
        return $rowAG->toArray();
    }
    
    public function existeDocenteEnAsignacion($idGrupoEscolar, $idMateriaEscolar) {
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idGrupoEscolar=?',$idGrupoEscolar)->where('idMateriaEscolar=?',$idMateriaEscolar);
        $rowAG = $tAG->fetchRow($select);
        
        if (is_null($rowAG)) {
            return false;
        }else{
            return true;
        }
    }
    
    public function getAsignacionesByIdDocente($idDocente){
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idDocente=?',$idDocente);
        $rowsAG = $tAG->fetchAll($select);
        
        return $rowsAG->toArray();
    }
    
    
}
<?php
class Encuesta_Data_DAO_Asignacion {
    
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
    
    
    
    
}
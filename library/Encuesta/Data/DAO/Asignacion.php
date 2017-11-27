<?php
class Encuesta_Data_DAO_Asignacion {
    
    private $tableAsignacionGrupo;
    
    private $tableMateriaEscolar;
    private $tableDocente;
    private $tableGrupoEscolar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        $this->tableMateriaEscolar = new Encuesta_Data_DbTable_MateriaEscolar($config);
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        //$this->tableDocente = new Encuesta_Data_Dbtable_Re
    }
    
    /**
     * 
     * @param int $idAsignacion
     */
    public function getObjByIdAsignacion($idAsignacion) {
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idAsignacionGrupo=?',$idAsignacion);
        $rowAsignacion = $tAG->fetchRow($select);
        
        
    }
    
    
    
    
}
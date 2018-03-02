<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_SeccionEncuesta {
    
    private $tableSeccionEncuesta;
    private $tableGrupoSeccion;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableSeccionEncuesta = new Encuesta_Data_DbTable_SeccionEncuesta($config);
        $this->tableGrupoSeccion = new Encuesta_Data_DbTable_GrupoSeccion($config);
    }
    
    public function getSeccionById($idSeccionEncuesta) {
        $tSE = $this->tableSeccionEncuesta;
        $select = $tSE->select()->from($tSE)->where('idSeccionEncuesta=?',$idSeccionEncuesta);
        $rowSeccionEncuesta = $tSE->fetchRow($select);
        
        return $rowSeccionEncuesta->toArray();
    }
    
    public function getSeccionesEncuestaByIdEncuesta($idEncuesta) {
        $tSE = $this->tableSeccionEncuesta;
        $select = $tSE->select()->from($tSE)->where('idEncuesta=?',$idEncuesta);
        $rowsSeccionesEncuesta = $tSE->fetchAll($select);
        
        return $rowsSeccionesEncuesta->toArray();
    }
    
    public function getGrupoSeccionById($idGrupoSeccion) {
        $tGS = $this->tableGrupoSeccion;
        $select = $tGS->select()->from($tGS)->where('idGrupoSeccion=?',$idGrupoSeccion);
        $rowGS = $tGS->fetchRow($select);
        
        return $rowGS->toArray();
    }
    
    public function getGruposSeccionByIdSeccionEncuesta($idSeccionEncuesta) {
        $tGS = $this->tableGrupoSeccion;
        
        $select = $tGS->select()->from($tGS)->where('idSeccionEncuesta=?',$idSeccionEncuesta);
        $rowsGS = $tGS->fetchAll($select);
        
        return $rowsGS->toArray();
    }
    
}
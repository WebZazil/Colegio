<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Deportes_Data_DAO_Competencia {
    
    private $tableCompetencia;
    private $tableEstatusCompetencia;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableCompetencia = new Deportes_Data_DbTable_Competencia($config);
        $this->tableEstatusCompetencia = new Deportes_Data_DbTable_EstatusCompetencia($config);
    }
    
    public function getAllEstatusCompetencias() {
        $tEC = $this->tableEstatusCompetencia;
        return $tEC->fetchAll()->toArray();
    }
    
    public function getEstatusCompetenciaById($idEstatus) {
        $tEC = $this->tableEstatusCompetencia;
        $select = $tEC->select()->from($tEC)->where('idEstatusCompetencia=?',$idEstatus);
        $rowEC = $tEC->fetchRow($select);
        
        return $rowEC->toArray();
    }
    
    public function addEstatusCompetencias($datos) {
        $tEC = $this->tableEstatusCompetencia;
        return $tEC->insert($datos);
    }
    
    public function editEstatusCompetencia($idEstatusCompetencia, $datos) {
        $tEC = $this->tableEstatusCompetencia;
        $where = $tEC->getAdapter()->quoteInto('idEstatusCompetencia=?', $idEstatusCompetencia);
        $tEC->update($datos, $where);
    }
    
    
    public function getAllCompetencias() {
        $tC = $this->tableCompetencia;
        return $tC->fetchAll()->toArray();
    }
    
    public function addCompetencia($datos) {
        $tC = $this->tableCompetencia;
        return $tC->insert($datos);
    }
    
    
    
}
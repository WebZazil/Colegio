<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Deportes_Data_DAO_Concursante {
    
    private $tableConcursante;
    private $tableEstatusConcursante;
    private $tableEquipo;
    private $table;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableConcursante = new Deportes_Data_DbTable_Concursante($config);
        $this->tableEstatusConcursante = new Deportes_Data_DbTable_EstatusConcursante($config);
        $this->tableEquipo = new Deportes_Data_DbTable_Equipo($config);
    }
    
    public function getAllEstatusConcursante() {
        $tEC = $this->tableEstatusConcursante;
        return $tEC->fetchAll()->toArray();
    }
    
    public function getEstatusConcursanteById($idEstatus) {
        $tEC = $this->tableEstatusConcursante;
        $select = $tEC->select()->from($tEC)->where('idEstatusConcursante=?',$idEstatus);
        $rowEC = $tEC->fetchRow($select);
        
        return $rowEC->toArray();
    }
    
    public function addEstatusConcursante($datos) {
        $tEC = $this->tableEstatusConcursante;
        return $tEC->insert($datos);
    }
    
    public function editEstatusConcursante($idEstatus, $datos) {
        $tEC = $this->tableEstatusConcursante;
        $where = $tEC->getAdapter()->quoteInto('idEstatusConcursante=?', $idEstatus);
        
        $tEC->update($datos, $where);
    }
    
    public function getConcursanteById($idConcursante) {
        $tCon = $this->tableConcursante;
        $select = $tCon->select()->from($tCon)->where('idConcursante=?',$idConcursante);
        $rowCon = $tCon->fetchRow($select);
        
        return $rowCon->toArray();
    }
    
    public function getConcursantesByEstatus($estatus) {
        $tECon = $this->tableEstatusConcursante;
        $select = $tECon->select()->from($tECon)->where('estatus=?',$estatus);
        $rowECon = $tECon->fetchRow($select)->toArray();
        
        $tCon = $this->tableConcursante;
        $select = $tCon->select()->from($tCon)->where('idEstatusConcursante=?', $rowECon['idEstatusConcursante']);
        $rowsCon = $tCon->fetchAll($select);
        
        return $rowsCon->toArray();
    }
    
    public function getConcursantesEquipo($idEquipo) {
        $tE = $this->tableEquipo;
        $select = $tE->select()->from($tE)->where('idEquipo=?',$idEquipo);
        $rowE = $tE->fetchRow($select)->toArray();
        
        $idsConcursantes = explode(',', $rowE['idsConcursantes']);
        
        $tCon = $this->tableConcursante;
        $select = $tCon->select()->from($tCon)->where('idConcursante IN (?)',$idsConcursantes);
        $rowsCon = $tCon->fetchAll($select);
        
        return $rowsCon->toArray();
    }
    
    public function getConcursantesByParams($params) {
        $tCon = $this->tableConcursante;
        $container = array();
        
        foreach ($params as $col => $val) {
            $select = $tCon->select()->from($tCon)->where($col.' LIKE ?', '%'.$val.'%');
            //print_r($select->__toString());
            $rowsCon = $tCon->fetchAll($select)->toArray();
            
            foreach ($rowsCon as $rowCon) {
                $container[] = $rowCon;
            }
        }
        
        return $container;
    }
    
    public function addConcursante($datos) {
        $tCon = $this->tableConcursante;
        return $tCon->insert($datos);
    }
}
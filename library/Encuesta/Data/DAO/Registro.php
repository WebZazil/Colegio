<?php
class Encuesta_Data_DAO_Registro {
    
    private $tableRegistro;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableRegistro = new Encuesta_Data_DbTable_Registro($config);
    }
    
    public function getAllRegistros() {
        return $this->tableRegistro->fetchAll()->toArray();
    }
    
    public function getRegistroById($idRegistro) {
        $tR = $this->tableRegistro;
        $select = $tR->select()->from($tR)->where('idRegistro=?',$idRegistro);
        $rowRegistro = $tR->fetchRow($select);
        
        return $rowRegistro->toArray();
    }
    
}
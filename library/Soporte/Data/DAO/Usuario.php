<?php

class Soporte_Data_DAO_Usuario {
    
    private $tableUsuario;
    private $tableRol;
    
    public function __construct($dbAdapter) {
        
        
        $this->tableUsuario = new Soporte_Data_DbTable_Usuario(array('db' => $dbAdapter));
        $this->tableRol = new Soporte_Data_DbTable_Rol(array('db' => $dbAdapter));
        
    }
    
    public function getUserRol($idUsuario) {
        $tUs = $this->tableUsuario;
        $tRol = $this->tableRol;
        
        $select = $tUs->select()->from($tUs)->where("idUsuario=?",$idUsuario);
        $rowUs = $tUs->fetchRow($select);
        
        $tRol->select()->from($tRol)->where("idRol=?",$rowUs->idRol);
        $rowRol = $tRol->fetchRow($select);
        
        return $rowRol->toArray();
    }
    
    
    
    
}
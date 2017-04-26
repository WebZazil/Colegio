<?php
/**
 * 
 */
class Soporte_Data_DAO_Equipo {
	
    private $tablaEquipo;
    
	function __construct() {
	    $dbAdapter = Zend_Registry::get('dbmodquerys');
		$this->tablaEquipo = new Soporte_Data_DbTable_Equipo(array('db' => $dbAdapter));
	}
    
    
    public function getUsuarios() {
        $tablaEquipo = $this->tablaEquipo;
        $select = $tablaEquipo->select()->distinct()->from($tablaEquipo, 'Usuario');
        $rowsUsuarios = $tablaEquipo->fetchAll($select);
        
        //print_r($rowsUsuarios->toArray());
        return $rowsUsuarios->toArray();
    }
    
    public function getUbicaciones() {
        $tablaEquipo = $this->tablaEquipo;
        $select = $tablaEquipo->select()->distinct()->from($tablaEquipo,'ubicacion');
        $rowUbicaciones = $tablaEquipo->fetchAll($select);
        
        return $rowUbicaciones->toArray();
    }
    
}

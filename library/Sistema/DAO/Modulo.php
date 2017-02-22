<?php
/**
 * 
 */
class Sistema_DAO_Modulo {
	
	private $tablaModulo;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaModulo = new Sistema_Model_DbTable_Modulo(array("db"=>$dbAdapter)); 
	}
	
	public function getConfig($modulo) {
		$tablaModulo = $this->tablaModulo;
		$select = $tablaModulo->select()->from($tablaModulo)->where("modulo=?",$modulo);
		$rowModulo = $tablaModulo->fetchRow($select);
		
		return $rowModulo->toArray();
	}
	
	
}

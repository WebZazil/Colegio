<?php
/**
 * 
 */
class Sistema_DAO_Reporte implements Sistema_Interfaces_IReporte {
	
	private $tablaReporte;
	private $tablaTipoReporte;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaTipoReporte = new Sistema_Model_DbTable_TipoReporte(array('db'=>$dbAdapter));
		$this->tablaReporte = new Sistema_Model_DbTable_Reporte(array('db'=>$dbAdapter));
	}
}

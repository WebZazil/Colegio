<?php

class Contabilidad_DAO_Divisa implements Contabilidad_Interfaces_IDivisa {
		
	private $tablaDivisa;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaDivisa= new Contabilidad_Model_DbTable_Divisa(array('db'=>$dbAdapter));
	}
	
	public function obtenerDivisas(){
		$tablaDivisa = $this->tablaDivisa;
		$rowDivisas = $tablaDivisa->fetchAll();
		
		$modelDivisas = array();
		
		foreach ($rowDivisas as $rowDivisa) {
			$modelDivisa = new Contabilidad_Model_Divisa($rowDivisa->toArray());
			$modelDivisa->setIdDivisa($rowDivisa->idDivisa);
			
			$modelDivisas[] = $modelDivisa;
		}
		
		return $modelDivisas;
	}
}
<?php

class Biblioteca_DAO_Subdivision{
	
	private $tableSubDivision;
	
	function __construct(){
		
		$dbAdapter = Zend_Registry::get("dbgenerale");
		
		$this->tableSubDivision = new Biblioteca_Model_DbTable_SubDivision(array('db'=>$dbAdapter));
	}
	
	
	public function getAllSubdivisiones()
	{
		$tablaSubDivision = $this->tableSubDivision;
		$rowSubdivisiones = $tablaSubDivision->fetchAll();
		
		if (is_null ($rowSubdivisiones)) {
			return null;
		}else{
			return $rowSubdivisiones->toArray();
		}
	}
}

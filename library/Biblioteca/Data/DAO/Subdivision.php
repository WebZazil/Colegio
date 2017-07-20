<?php

class Biblioteca_Data_DAO_Subdivision{
	
	private $tableSubDivision;
	
	function __construct(){
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->tableSubDivision = new Biblioteca_Data_DbTable_SubDivision(array('db'=>$dbAdapter));
	}
	
	
	public function addSubdivision($data)
	{
		
		$this->tableSubDivision->insert($data);
		
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

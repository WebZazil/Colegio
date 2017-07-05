<?php
/**
 * 
 */
 class Biblioteca_DAO_SubDivGeo{
 	
	private $tableSubDivGeo;
	
	function __construct() 
	{
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->tableSubDivGeo = new Biblioteca_Model_DbTable_SubDivGeo(array("db"=>$dbAdapter));
	}
	
	public function getAllPaises()
	{
		$tablaSubDivGeo = $this->tableSubDivGeo;
		$rowsPaises = $tablaSubDivGeo->fetchAll();
		
		if(!is_null($rowsPaises)){
			
			return $rowsPaises->toArray();
		}else{
			return null;
		}
	}
 }

<?php
/**
 * 
 */
 class Biblioteca_DAO_Idioma{
 	
	private $tableIdioma;
	
	function __construct($dbAdapter) 
	{
		$dbAdapter = Zend_Registry::get("dbgenerale");
		$this->tableIdioma = new Biblioteca_Model_DbTable_Idioma(array("db"=>$dbAdapter));
	}
	
	public function getAllIdiomas()
	{
		$tablaidioma = $this->tableIdioma;
		$rowsidiomas = $tablaidioma->fetchAll();
		
		if(!is_null($rowsidiomas)){
			
			return $rowsidiomas->toArray();
		}else{
			return null;
		}
	}
 }

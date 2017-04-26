<?php
/*
 * 
 */
 
 class Biblioteca_DAO_Editorial{
 	
	private $tableEditorial;
	
	function __construct($dbAdapter){
		
		$dbAdapter = Zend_Registry::get("dbgenerale");
		$this->tableEditorial = new Biblioteca_Model_DbTable_Editorial(array("db"=>$dbAdapter));
		
	}
	
	public function getAllEditoriales()
	{
		$tablaEditorial = $this->tableEditorial;
		$rowsEditorial = $tablaEditorial->fetchAll();
		
		if(!is_null($rowsEditorial)){
			return $rowsEditorial->toArray();
		}else{
			return null;
		}
	}
 }
 

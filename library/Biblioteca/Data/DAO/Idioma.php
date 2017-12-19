<?php
/**
 * 
 */
 class Biblioteca_Data_DAO_Idioma{
 	
	private $tableIdioma;
	
	function __construct() 
	{
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->tableIdioma = new Biblioteca_Data_DbTable_Idioma(array("db"=>$dbAdapter));
	}
	
		public function agregarIdioma($data)
	{
		$this->tableIdioma->insert($data);
	}
	
	public function getAllIdiomas()
	{
		$tablaidioma = $this->tableIdioma;
		$select = $tablaidioma->select()->from($tablaidioma)->order("idioma ASC");
		$rowsidiomas = $tablaidioma->fetchAll($select);
		
		if(!is_null($rowsidiomas)){
			
			return $rowsidiomas->toArray();
		}else{
			return null;
		}
	}
 }

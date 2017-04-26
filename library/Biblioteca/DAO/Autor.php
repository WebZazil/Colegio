<?php
/*
 * 
 */
 
 class Biblioteca_DAO_Autor{
 	
 	private $tableAutor;
	
	function __construct(){
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->tableAutor = new Biblioteca_Model_DbTable_Autor(array("db"=>$dbAdapter));
	}
	
	
	public function agregarAutor(Biblioteca_Model_Autor $autor)
	{
		$tablaAutor = $this->tableAutor;
		$tablaAutor->insert($autor->toArray());
	}
	
	
	public function getAllAutores()
	{
		$tablaAutor = $this->tableAutor;
		$rowAutores = $tablaAutor->fetchAll();
		
		if (!is_null($rowAutores)) {
			return $rowAutores->toArray();
		}else{
			return null;
		}
	}
 }

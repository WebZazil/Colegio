<?php

/**
 * 
 */
 
 class Biblioteca_DAO_Ejemplar implements Biblioteca_Interfaces_IEjemplar{
 	
	private $tableEjemplar;
	
	function __construct(){
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableEjemplar = new Biblioteca_Model_DbTable_Ejemplar(array('db'=>$dbAdapter));
	}
	 
	 public function agregarEjemplar(Biblioteca_Model_Ejemplar $ejemplar)
	 {
		 $tablaEjemplar = $this->tableEjemplar;
		 $tablaEjemplar->insert($ejemplar->toArray());
	 }
	
 }
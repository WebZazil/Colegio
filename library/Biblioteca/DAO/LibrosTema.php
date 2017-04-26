<?php

/**
 * 
 */
 
 class Biblioteca_DAO_LibrosTema implements Biblioteca_Interfaces_ILibrosTema {
 	
	private $tableLibrosTema;
	private $tableLibro;
	private $tableTema;
	
	
	function __construct()
	{
		$dbAdapter = Zend_Registry::get("dbgenerale");
		
		$this->tableLibrosTema = new Biblioteca_Model_DbTable_LibroTema(array('db'=>$dbAdapter));
		$this->tableLibro = new Biblioteca_Model_DbTable_Libro(array('db'=>$dbAdapter));
		$this->tableTema = new Biblioteca_Model_DbTable_Tema(array('db'=>$dbAdapter));
	}
	
	public function agregarLibrosTema($idTema,$idLibro)
	{
		$tablaLibrosTema = $this->tableLibrosTema;
		$select = $tablaLibrosTema->select()->from($tablaLibrosTema)->where("idTema=?",$idTema);
		$rowLibrosTema = $tablaLibrosTema->fetchRow($select);
		
		if (is_null($rowLibrosTema)) {
			//No existe lamateria en la tabla
			
			$data = array();
			$data["idTema"] = $idTema;
			$data["idsLibro"] = $idLibro;
			$tablaLibrosTema->insert($data);
		}else{// Si ya existe el registro de lamateria con id: $idTema
			
			$idsLibro = $rowLibrosTema->idsLibro;
			$arrayIdsLibro = explode(",", $idsLibro);
			if (in_array($idLibro, $arrayIdsLibro)) {
				// esta en los ids
			}else{
				//no esta en los ids
				$arrayIdsLibro[] =  $idLibro;
				$idsLibro = implode(",", $arrayIdsLibro);
				$rowLibrosTema->idsLibro = $idsLibro;
				$rowLibrosTema->save();
				/*
				$data = array("idsLibro"=>$idsLibro);
				$where = $tablaLibrosTema->getDefaultAdapter()->quoteInto("idTema=?",$idTema);
				try{
					$tableLibrosTema->update($data, $where);
				}catch(Exception $ex){
					print_r($ex->getMessage());
				}
				*/
			}
			
		}
		
 	
	}
 }

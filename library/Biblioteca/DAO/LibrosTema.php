<?php

/**
 * 
 */
 
 class Biblioteca_DAO_LibrosTema implements Biblioteca_Interfaces_ILibrosTema {
 	
	private $tableLibrosTema;
	private $tableRecurso;
	private $tableTema;
	
	
	function __construct()
	{
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableLibrosTema = new Biblioteca_Model_DbTable_LibrosTema(array('db'=>$dbAdapter));
		$this->tableRecurso = new Biblioteca_Model_DbTable_Recurso(array('db'=>$dbAdapter));
		$this->tableTema = new Biblioteca_Data_DbTable_Tema(array('db'=>$dbAdapter));
	}
	
	public function agregarLibrosTema($idTema,$idRecurso)
	{
		$tablaLibrosTema = $this->tableLibrosTema;
		$select = $tablaLibrosTema->select()->from($tablaLibrosTema)->where("idTema=?",$idTema);
		$rowLibrosTema = $tablaLibrosTema->fetchRow($select);
		
		if (is_null($rowLibrosTema)) {
			//No existe el tema en la tabla
			
			$data = array();
			$data["idTema"] = $idTema;
			$data["idsRecurso"] = $idRecurso;
			$tablaLibrosTema->insert($data);
		}else{// Si ya existe el registro de lamateria con id: $idTema
			
			$idsRecurso = $rowLibrosTema->idsRecurso;
			$arrayIdsRecurso = explode(",", $idsRecurso);
			if (in_array($idRecurso, $arrayIdsRecurso)) {
				// esta en los ids
			}else{
				//no esta en los ids
				$arrayIdsRecurso[] =  $idRecurso;
				$idsRecurso = implode(",", $arrayIdsRecurso);
				$rowLibrosTema->idsRecurso = $idsRecurso;
				$rowLibrosTema->save();
				/*
				$data = array("idsLibro"=>$idsLibro);
				$where = $tablaLibrosTema->getDefaultAdapter()->quoteInto("idTema=?",$idTema);
				try{
					$tableLibrosTema->update($data, $where);
				}catch(Exception $ex){
					print_r($ex->getMessage());
				}
				 * 
				*/
			}
			
		}
		
 	
	}
 }

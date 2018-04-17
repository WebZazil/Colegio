<?php

/**
 * 
 */
 
 class Biblioteca_DAO_Temasrecurso implements Biblioteca_Interfaces_ILibrosTema {
 	
	private $tableTemasRecurso;
	private $tableRecurso;
	private $tableTema;
	
	
	function __construct($dbAdapter)
	{
		//$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
	    $config = array('db' => $dbAdapter);
		
	  $this->tableTemasRecurso = new Biblioteca_Model_DbTable_Temasrecurso($config);
	  $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
	    $this->tableTema = new Biblioteca_Data_DbTable_Tema($config);
	}
	
	public function agregarLibrosTema($datos)
	{
		$tTR = $this->tableTemasRecurso;
		$select = $tTR->select()->from($tTR)->where("idRecurso=?",$datos['idRecurso']);
		$rowTR = $tTR->fetchRow($select);
		
		if (is_null($rowTR)) {
			//No existe el tema en la tabla
			
			$data = array();
			$data["idRecurso"] = $datos['idRecurso'];
			$data["idsTemas"] = implode(',', array($datos['idTema']));
			$tTR->insert($data);
		}else{ // Si ya existe el registro de lamateria con id: $idTema
			
		    $idsTema = explode(',', $rowTR->idsTemas);
			// $arrayIdsTema = explode(",", $idsTema);
			//in_array($needle, $haystack)
			if (in_array($datos['idTema'], $idsTema) ) {
				// esta en los ids
			}else{
			    $idsTema[] = $datos['idTema'];
				//no esta en los ids
				//$arrayIdsTema[] =  $idsTema;
				$idsTema= implode(",", $arrayIdsTema);
				$rowTR->idsTemas = $idsTema;
				$rowTR->save();
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
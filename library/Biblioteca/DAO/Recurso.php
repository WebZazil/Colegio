<?php

/**
 * 
 */
 
 class Biblioteca_DAO_Recurso implements Biblioteca_Interfaces_IRecurso{
 	
	private $tableRecurso;
	
	function __construct(){
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableRecurso = new Biblioteca_Model_DbTable_Recurso(array('db'=>$dbAdapter));
		$this->tableAutor = new Biblioteca_Data_DbTable_Autor(array('db'=>$dbAdapter));
	}

	 
	 public function agregarRecurso(Biblioteca_Model_Recurso $recurso)
	 {
	 	$tablaRecurso = $this->tableRecurso;
		$select = $tablaRecurso->select()->from($tablaRecurso)->where("idRecurso=?",$idRecurso);
		$rowRecursos = $tablaRecurso->fetchRow($select);
		
		if(is_null($rowRecursos)){
			
			$data = array();
			$tableRecurso->insert($data);
			
		}else{
			$idsAutor = $rowRecursos->idsAutor;
			$arrayIdsAutor =  explode(",", $idsAutor);
			if (in_array($idAutor, $arrayIdsAutor)){
				
			}else{
				
				$arrayIdsAutor[] = $idAutor;
				$idsAutor = implode(",",$arrayIdsAutor);
				if (in_array($idsAutor, $arrayIdsAutor));
				$rowRecursos->idsRecurso = $idsAutor;
				$rowRecursos->save();
			}
		}
		

		
		/** $tablaRecurso = $this->tableRecurso;
		 $tablaRecurso->insert($recurso->toArray());**/
	 }
	 
	 	public function getAllRecursos(){
	 		
		$tablaRecurso = $this->tableRecurso;
		$rowsRecurso = $tablaRecurso->fetchAll();
		
		if(!is_null($rowsRecurso)){
			$arrRecursos = $rowsRecurso->toArray();
			$arrModelRecurso = array();
			foreach ($arrRecursos as $arrRecurso) {
				$modelRecurso = new Biblioteca_Model_Recurso($arrRecurso);
				$arrModelRecurso[] = $modelRecurso;
			}
			return $arrModelRecurso;
		}else{
			return array();
		}
		
	}
	 
 }
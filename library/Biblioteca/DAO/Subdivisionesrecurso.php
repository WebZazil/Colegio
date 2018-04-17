<?php

/**
 * 
 * 
 */
  class Biblioteca_DAO_Subdivisionesrecurso{
 	
	private $tableSubdivisionesRecurso;
	private $tableRecurso;
	private $tableSubdivision;
	
	function __construct($dbAdapter)
	{
		//$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$config = array('db' => $dbAdapter);
		
		$this->tableSubdivisionesRecurso = new Biblioteca_Model_DbTable_Subdivisionesrecurso($config);
		$this->tableRecurso = new Biblioteca_Model_DbTable_Recurso($config);
		$this->tableSubdivision = new Biblioteca_Data_DbTable_Subdivision($config);
	}
	public function agregaSubdivisionesR($datos)
	{
		$tablaSR = $this->tableSubdivisionesRecurso;
		$select = $tablaSR->select()->from($tablaSR)->where("idRecurso=?", $idRecurso);
		$rowSR = $tablaSR->fetchRow($select);
		
		if (is_null($rowSR)) {
			//No existe la subdivision en la tabla
			
			$data = array();
			$data["idRecurso"] = $datos['idRecurso'];
			$data["idsSubdivisiones"] = implode(',', array($datos['idSubdivision']));
			$tablaSR->insert($data);
			
		}else {//si ya existe el registro de la subdivisio con id: $idSubDIvision
		    
		    $idsSubdivision = explode(',', $rowSR->idsSubdivisiones);
		    // $arrayIdsTema = explode(",", $idsTema);
		    //in_array($needle, $haystack)
		    if (in_array($datos['idSubdivision'], $idsSubdivision) ) {
		        // esta en los ids
		    }else{
		        $idsSubdivisiones[] = $datos['idSubdivisione'];
		        //no esta en los ids
		        //$arrayIdsTema[] =  $idsTema;
		        $idsTema= implode(",", $arrayIdsSubdivisiones);
		        $rowSR->idsSubdivisiones = $idsSubdivision;
		        $rowSR->save();
		  
		    }
		/*	$idsSubdivision = $rowSubdivisionesLibro->idsSubdivision;
			$arrayIdsSubdivision = explode(",", $idsSubdivision);
			if (in_array($idSubdivision,$arrayIdsSubdivision)) {
				//esta en los ids
			}else {
				//no estan los ids
				$arrayIdsSubdivision[] = $idSubdivision;
				$idsSubdivision = implode(",", $arrayIdsSubdivision);
				$rowSubdivisionesLibro->idsSubdivision = $idsSubdivision;
				$rowSubdivisionesLibro->save();
			}*/
		}
	}
	
 }
<?php

/**
 * 
 * 
 */
  class Biblioteca_DAO_SubdivisionesTema implements Biblioteca_Interfaces_ITemasSubdivision{
 	
	private $tableST;
	private $tableTema;
	private $tableSubdivision;
	
	function __construct($dbAdapter)
	{
		//$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
	    $config = array('db' => $dbAdapter);
		
	    $this->tableTemasST = new Biblioteca_Model_DbTable_Subdivisionestema( $config);
	    $this->tableTema = new Biblioteca_Data_DbTable_Tema($config);
		$this->tableSubdivision = new Biblioteca_Model_DbTable_SubDivision( $config);
	}
	public function agregarSubdivisionesT( $datos)
	{
		$tablaST = $this->tableST;
		$select = $tablaST->select()->from($tablaST)->where("idTema=?", $idTema);
		$rowST = $tablaST->fetchRow($select);
		
		if (is_null($rowST)) {
			//No existe la subdivision en la tabla
			
			$data = array();
			$data["idTema"] = $datos['idTema'];
			$data["idsSuvdivisiones"] = implode(',', array($datos['idSubdivision']));
			$tablaST->insert($data);
			
		}else {//si ya existe el registro de la subdivisio con id: $idSubDIvision
			
			$idsSubdivision = $rowST->idsSubdivision;
			$arrayIdsSubdivision = explode(",", $idsSubdivision);
			if (in_array($idSubdivision,$arrayIdsSubdivision)) {
				//esta en los ids
			}else {
				//no estan los ids
			    $idsSubdivision = explode(',', $rowST->idsSubdivisiones);
			    // $arrayIdsTema = explode(",", $idsTema);
			    //in_array($needle, $haystack)
			    if (in_array($datos['idSubdivision'], $idsSubdivision) ) {
			        // esta en los ids
			    }else{
			        $idsSubdivision[] = $datos['idTema'];
			        //no esta en los ids
			        //$arrayIdsTema[] =  $idsTema;
			        $idsSubdivision= implode(",", $arrayIdsTema);
			        $rowST->idsSubdivisiones = $idsSubdivision;
			        $rowST->save();
			  
			    }
			}
		}
	}
	
 }
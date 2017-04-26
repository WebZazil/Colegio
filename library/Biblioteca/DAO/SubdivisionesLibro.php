<?php

/**
 * 
 * 
 */
  class Biblioteca_DAO_SubdivisionesLibro implements Biblioteca_Interfaces_ISubdivisionesLibro{
 	
	private $tableSubdivisionesLibro;
	private $tableLibro;
	private $tableSubDivision;
	
	function __construct()
	{
		$dbAdapter = Zend_Registry::get("dbgenerale");
		
		$this->tableSubdivisionesLibro = new Biblioteca_Model_DbTable_SubdivisionesLibro(array('db'=>$dbAdapter));
		$this->tableLibro = new Biblioteca_Model_DbTable_Libro(array('db'=>$dbAdapter));
		$this->tableSubDivision = new Biblioteca_Model_DbTable_SubDivision(array('db'=>$dbAdapter));
	}
	public function agregarSubdivisionesLibro($idLibro, $idSubDivision)
	{
		$tablaSubdivisionesLibro = $this->tableSubdivisionesLibro;
		$select = $tablaSubdivisionesLibro->select()->from($tablaSubdivisionesLibro)->where("idLibro=?", $idLibro);
		$rowSubdivisionesLibro = $tablaSubdivisionesLibro->fetchRow($select);
		
		if (is_null($rowSubdivisionesLibro)) {
			//No existe la subdivision en la tabla
			
			$data = array();
			$data["idLibro"] = $idLibro;
			$data["idsSubdivision"] = $idSubDivision;
			$tablaSubdivisionesLibro->insert($data);
			
		}else {//si ya existe el registro de la subdivisio con id: $idSubDIvision
			
			$idsSubdivision = $rowSubdivisionesLibro->idsSubdivision;
			$arrayIdsSubdivision = explode(",", $idsSubdivision);
			if (in_array($idSubDivision,$arrayIdsSubdivision)) {
				//esta en los ids
			}else {
				//no estan los ids
				$arrayIdsSubdivision[] = $idSubDivision;
				$idsSubdivision = implode(",", $arrayIdsSubdivision);
				$rowSubdivisionesLibro->idsSubdivision = $idsSubdivision;
				$rowSubdivisionesLibro->save();
			}
		}
	}
	
 }
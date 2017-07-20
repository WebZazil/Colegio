<?php

/**
 * 
 * 
 */
  class Biblioteca_DAO_SubdivisionesLibro implements Biblioteca_Interfaces_ISubdivisionesLibro{
 	
	private $tableSubdivisionesLibro;
	private $tableRecurso;
	private $tableSubdivision;
	
	function __construct()
	{
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableSubdivisionesLibro = new Biblioteca_Model_DbTable_SubdivisionesLibro(array('db'=>$dbAdapter));
		$this->tableRecurso = new Biblioteca_Model_DbTable_Recurso(array('db'=>$dbAdapter));
		$this->tableSubdivision = new Biblioteca_Data_DbTable_Subdivision(array('db'=>$dbAdapter));
	}
	public function agregarSubdivisionesLibro($idRecurso, $idSubdivision)
	{
		$tablaSubdivisionesLibro = $this->tableSubdivisionesLibro;
		$select = $tablaSubdivisionesLibro->select()->from($tablaSubdivisionesLibro)->where("idRecurso=?", $idRecurso);
		$rowSubdivisionesLibro = $tablaSubdivisionesLibro->fetchRow($select);
		
		if (is_null($rowSubdivisionesLibro)) {
			//No existe la subdivision en la tabla
			
			$data = array();
			$data["idRecurso"] = $idRecurso;
			$data["idsSubdivision"] = $idSubdivision;
			$tablaSubdivisionesLibro->insert($data);
			
		}else {//si ya existe el registro de la subdivisio con id: $idSubDIvision
			
			$idsSubdivision = $rowSubdivisionesLibro->idsSubdivision;
			$arrayIdsSubdivision = explode(",", $idsSubdivision);
			if (in_array($idSubdivision,$arrayIdsSubdivision)) {
				//esta en los ids
			}else {
				//no estan los ids
				$arrayIdsSubdivision[] = $idSubdivision;
				$idsSubdivision = implode(",", $arrayIdsSubdivision);
				$rowSubdivisionesLibro->idsSubdivision = $idsSubdivision;
				$rowSubdivisionesLibro->save();
			}
		}
	}
	
 }
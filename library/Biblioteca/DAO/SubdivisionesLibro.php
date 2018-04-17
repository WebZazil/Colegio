<?php

/**
 * 
 * 
 */
  class Biblioteca_DAO_SubdivisionesLibro{
 	
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
	public function agregaSubdivisionesLibro($idRecurso, $idSubdivision)
	{
		$tablaSubdivisionesLibro = $this->tableSubdivisionesRecurso;
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
<?php

/**
 * 
 * 
 */
  class Biblioteca_DAO_TemasSubdivision implements Biblioteca_Interfaces_ITemasSubdivision{
 	
	private $tableTemasSubdivision;
	private $tableTema;
	private $tableSubdivision;
	
	function __construct()
	{
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableTemasSubdivision = new Biblioteca_Model_DbTable_TemasSubdivision(array('db'=>$dbAdapter));
		$this->tableTema = new Biblioteca_Data_DbTable_Tema(array('db'=>$dbAdapter));
		$this->tableSubdivision = new Biblioteca_Model_DbTable_SubDivision(array('db'=>$dbAdapter));
	}
	public function agregarTemasSubdivision( $idSubdivision, $idTema)
	{
		$tablaTemasSubdivision = $this->tableTemasSubdivision;
		$select = $tablaTemasSubdivision->select()->from($tablaTemasSubdivision)->where("idSubdivision=?", $idSubdivision);
		$rowTemasSubdivision = $tablaTemasSubdivision->fetchRow($select);
		
		if (is_null($rowTemasSubdivision)) {
			//No existe la subdivision en la tabla
			
			$data = array();
			$data["idSubdivision"] = $idSubdivision;
			$data["idsTema"] = $idTema;
			$tablaTemasSubdivision->insert($data);
			
		}else {//si ya existe el registro de la subdivisio con id: $idSubDIvision
			
			$idsTema = $rowTemasSubdivision->idsTema;
			$arrayIdsTema = explode(",", $idsTema);
			if (in_array($idTema,$arrayIdsTema)) {
				//esta en los ids
			}else {
				//no estan los ids
				$arrayIdsTema[] = $idTema;
				$idsTema = implode(",", $arrayIdsTema);
				$rowTemasSubdivision->idsTema = $idsTema;
				$rowTemasSubdivision->save();
			}
		}
	}
	
 }
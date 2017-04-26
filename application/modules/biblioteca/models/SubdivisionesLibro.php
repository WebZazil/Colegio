<?php

class Biblioteca_Model_SubdivisionesLibro
{
	
	
	private $idLibro;
	
	public function getIdLibro()
	{
		return $this->idLibro;
	}
	
	public function setIdLibro($idLibro)
	{
		$this->idLibro = $idLibro;
	}
	
	private $idsSubdivision;
	
	public function getIdsSubdivision()
	{
		return $this->idsSubdivision;
	}
	
	public function setIdsSubdivision($idsSubdivision)
	{
		$this->idsSubdivision = $idsSubdivision;
	}
	
	public function  __construct($datos)
	{
		$this->idLibro = $datos["idLibro"];
		$this->idsSubdivision = $datos["idsSubdivision"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idLibro"] = $this->idLibro;
		$datos["idsSubdivision"] = $this->idsSubdivision;
		
		return $datos;
	}


}


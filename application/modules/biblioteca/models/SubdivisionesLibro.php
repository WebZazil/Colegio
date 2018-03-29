<?php

class Biblioteca_Model_SubdivisionesLibro
{
	
	
	private $idRecurso;
	
	public function getIdRecurso()
	{
		return $this->idRecurso;
	}
	
	public function setIdRecurso($idRecurso)
	{
		$this->idRecurso = $idRecurso;
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
	    if (array_key_exists("idRecurso", $datos)) $this->idRecurso = $datos["idRecurso"];
	    
		$this->idsSubdivision = $datos["idsSubdivision"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idRecurso"] = $this->idRecurso;
		$datos["idsSubdivision"] = $this->idsSubdivision;
		
		return $datos;
	}


}


<?php

class Biblioteca_Model_TemasSubdivision
{

	private $idsTema;
	
	public function getIdsTema()
	{
		return $this->idsTema;
	}
	
	public function setIdsTema($idsTema)
	{
		$this->idsTema = $idsTema;
	}
	
	private $idSubduivision;
	
	public function getIdSubdivision()
	{
		return $this->idSubduivision;
	}
	
	public function setIdSubdivision($idSubdivision)
	{
		$this->idsSubduivision = $idSubdivision;
	}
	
	
	public function __construct($datos)
	{
		$this->idSubduivision = $datos["idSubdivision"];
		$this->idsTema = $datos["idsTema"];
	}

	public function toArray()
	{
		$datos = array();
		
		$datos["idSubdivision"] = $this->idSubduivision;
		$datos["idsTema"] = $this->idsTema;
		
		return $datos;
	}

}


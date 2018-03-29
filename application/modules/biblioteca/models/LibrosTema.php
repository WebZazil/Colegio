<?php

class Biblioteca_Model_LibrosTema
{
	
	private  $idsTema;
	
	public function getIdsTema()
	{
		return $this->idsTema;
	}
	
	public function setIdsTema($idsTema)
	{
		$this->idTema = $idsTema;
	}
	
	private $idRecurso;
	
	public function getIdRecurso()
	{
		return $this->idRecurso;
	}
	
	public function setIdsRecurso($idRecurso)
	{
		$this->idsRecurso = $idRecurso;
	}
	
	
	public function __construct($datos)
	{
	    if (array_key_exists("idRecurso", $datos)) $this->idRecurso = $datos["idRecurso"];
	    
		$this->idsTema = $datos["idsTema"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idRecurso"] = $this->idRecurso;
		$datos["idsTema"] = $this->idsTema;
		
		
		return $datos;
	}
	

}


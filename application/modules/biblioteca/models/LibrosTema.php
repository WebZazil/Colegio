<?php

class Biblioteca_Model_LibrosTema
{
	
	private  $idTema;
	
	public function getIdTema()
	{
		return $this->idTema;
	}
	
	public function setIdTema($idTema)
	{
		$this->idTema = $idTema;
	}
	
	private $idsRecurso;
	
	public function getIdsRecurso()
	{
		return $this->idsRecurso;
	}
	
	public function setIdsRecurso($idsRecurso)
	{
		$this->idsRecurso = $idsRecurso;
	}
	
	
	public function __construct($datos)
	{
		$this->idTema = $datos["idTema"];
		$this->idsRecurso = $datos["idsRecurso"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idTema"] = $this->idTema;
		$datos["idsRecurso"] = $this->idsRecurso;
		
		return $datos;
	}
	

}


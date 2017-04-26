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
	
	private $idsLibro;
	
	public function getIdsLibro()
	{
		return $this->idsLibro;
	}
	
	public function setIdsLibro($idsLibro)
	{
		$this->idsLibro = $idsLibro;
	}
	
	
	public function __construct($datos)
	{
		$this->idTema = $datos["idTema"];
		$this->idsLibro = $datos["idsLibro"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idTema"] = $this->idTema;
		$datos["idsLibro"] = $this->idsLibro;
		
		return $datos;
	}
	

}


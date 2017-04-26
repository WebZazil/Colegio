<?php

class Biblioteca_Model_Recurso
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
	
	private $titulo;
	
	public function getTitulo()
	{
		return $this->titulo;
	}
	
	public function setTitulo($titulo)
	{
		$this->titulo = $titulo;
	}
	
	private $subtitulo;
	
	public function getSubtitulo()
	{
		return $this->subtitulo;
	}
	
	public function setSubtitulo($subtitulo)
	{
		$this->subtitulo = $subtitulo;
	}
	
	
	private $idAutor;
	
	public function getIdAutor()
	{
		return $this->idAutor;
	}
	
	public function setIdAutor($idAutor)
	{
		$this->idAutor = $idAutor;
	}
	
	private $idMaterial;
	
	public function getIdMaterial()
	{
		return $this->idMaterial;	
	}
	
	public function setIdMaterial($idMaterial)
	{
		$this->idMaterial = $idMaterial;
	}
	
	private $idColeccion;
	
	public function getIdColeccion()
	{
		return $this->idColeccion;	
	}
	
	public function setIdColeccion($idColeccion)
	{
		$this->idColeccion = $idColeccion;
	}
	
	
	private $idClasificacion;
	
	public function getIdClasificacion()
	{
		return $this->idClasificacion;
	}
	
	public function setIdClasificacion($idClasificacion)
	{
		$this->idClasificacion = $idClasificacion;
	}
	
	private $fechaAlta;
	
	public function getFechaAlta()
	{
		return $this->fechaAlta;
	}
	
	public function setFechaAlta($fechaAlta)
	{
		$this->fechaAlta = $fechaAlta;
	}
	
	public function __construct($datos)
	{
		if (array_key_exists("idRecurso", $datos)) $this->idRecurso = $datos["idRecurso"];
			
		
		$this->titulo = $datos["titulo"];
		$this->subtitulo = $datos["subtitulo"];
		$this->idAutor = $datos["idAutor"];
		$this->idMaterial = $datos["idMaterial"];
		$this->idColeccion = $datos["idColeccion"];
		$this->idClasificacion = $datos["idClasificacion"];
		
		if (! array_key_exists('fechaAlta', $datos)){
			$this->codigoBarras = "";
		}else{
			$this->fechaAlta= $datos["fechaAlta"];
		} 
		
	}
	
	public function toArray()
	{
		
		$datos = array();
		
		$datos["idRecurso"] = $this->idRecurso;
		$datos["titulo"] = $this->titulo;
		$datos["subtitulo"] = $this->subtitulo;
		$datos["idAutor"] = $this->idAutor;
		$datos["idMaterial"] = $this->idMaterial;
		$datos["idColeccion"] = $this->idColeccion;
		$datos["idClasificación"] = $this->idClasificacion;
		
		return $datos;
	}

}


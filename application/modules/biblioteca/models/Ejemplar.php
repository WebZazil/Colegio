<?php

class Biblioteca_Model_Ejemplar
{
	private $idEjemplar;
	
	public function getIdEjemplar()
	{
		return $this->idEjemplar;
	}
	
	public function setIdEjemplar($idEjemplar)
	{
		$this->idEjemplar = $idEjemplar;
	}
	
	private $idRecurso;
	
	public function getIdRecurso()
	{
		return $this->idRecurso;
	}
	
	public function setIdRecurso($idRecurso)
	{
		$this->idRecurso = $idRecurso;
	}
	
	private $publicado;
	
	public function getPublicado()
	{
		return $this->publicado;
	}
	
	public function setPublicado($publicado)
	{
		$this->publicado = $publicado;		
	}
	
	private $idEditorial;
	
	public function getIdEditorial()
	{
		return $this->idEditorial;
	}
	
	public function setIdEditorial($idEditorial)
	{
		$this->idEditorial = $idEditorial;
	}
	
	private $paginas;
	
	public function getPaginas()
	{
		return $this->paginas;
	}
	
	public function setPaginas($paginas)
	{
		$this->paginas = $paginas;
	}
	
	private $isbn;
	
	public function getIsbn()
	{
		return $this->isbn;
	}
	
	public function setIsbn($isbn)
	{
		$this->isbn = $isbn;
	}
	
	private $codigoBarras;
	
	public function getCodigoBarras()
	{
		return $this->codigoBarras;
	}
	
	public function setCodigoBarras($codigoBarras)
	{
		$this->codigoBarras = $codigoBarras;
	}
	
	private $issn;
	
	public function getIssn()
	{
		return $this->issn;
	}
	
	public function setIssn($issn)
	{
		$this->issn = $issn;
	}
	
	private $noClasif;
	
	public function getNoClasif()
	{
		return $this->noClasif;
	}
	
	public function setNoClasif($noClasif)
	{
		$this->noClasif = $noClasif;
	}
	
	private $noItem;
	
	public function getNoItem()
	{
		return $this->noItem;
	}
	
	public function setNoItem($noItem)
	{
		$this->noItem = $noItem;
	}
	
	private $noEdicion;
	
	public function getNoEdicion()
	{
		return $this->noEdicion;
	}
	
	public function setNoEdicion($noEdicion)
	{
		$this->noEdicion = $noEdicion;
	}
	
	private $idSubDivGeo;
	
	public function getIdSubDivGeo ()
	{
		return $this->idSubDivGeo;
	}
	
	public function setIdPaisPub($idPaisPub)
	{
		$this->idSubDivGeo = $idSubDivGeo;
	}
	
	private $dimension;
	
	public function getDimension()
	{
		return $this->dimension;
	}
	
	public function setDimension($dimension)
	{
		$this->dimension = $dimension;
	}
	
	private $serie;
	
	public function getSerie()
	{
		return $this->serie;
	}
	
	public function setSerie($serie)
	{
		$this->serie = $serie;
	}
	
	private $asientoPrin;
	
	public function getAsientoPrin()
	{
		return $this->asientoPrin;
	}
	
	public function setAsientoPrin($asientoPrin)
	{
		$this->asientoPrin = $asientoPrin;
	}
	
	private $volumen;
	
	public function getVolumen()
	{
		return $this->volumen;
	}
	
	public function setVolumen($volumen)
	{
		$this->volumen = $volumen;
	}
	
	private $idIdioma;
	
	public function getIdIdioma()
	{
		return $this->idIdioma;
	}
	
	public function setIdIdioma($idIdioma)
	{
		$this->idIdioma = $idIdioma;
	}
	
	public function __construct($datos)
	{
		if(array_key_exists("idEjemplar", $datos)) $this->idEjemplar = $datos["idEjemplar"];
		
		$this->idRecurso = $datos["idRecurso"];
		$this->publicado = $datos["publicado"];
		$this->idEditorial = $datos["idEditorial"];
		$this->paginas = $datos["paginas"];
		$this->isbn = $datos["isbn"];
		
		if (! array_key_exists('codigoBarras', $datos)){
			$this->codigoBarras = "";
		}else{
			$this->codigoBarras = $datos["codigoBarras"];
		} 
		
		$this->issn = $datos["issn"];
		$this->noClasif= $datos["noClasif"];
		$this->noItem = $datos["noItem"];
		$this->noEdicion = $datos["noEdicion"];
		$this->idSubDivGeo = $datos["idSubDivGeo"];
		$this->dimension = $datos["dimension"];
		$this->serie = $datos["serie"];
		$this->asientoPrin = $datos["asientoPrin"];
		$this->volumen = $datos["volumen"];
		$this->idIdioma = $datos["idIdioma"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idEjemplar"] = $this->idEjemplar;
		$datos["idRecurso"] = $this->idRecurso;
		$datos["publicado"] = $this->publicado;
		$datos["idEditorial"] = $this->idEditorial;
		$datos["paginas"] = $this->paginas;
		$datos["isbn"] = $this->isbn;
		$datos["codigoBarras"] = $this->codigoBarras;
		$datos["issn"] = $this->issn;
		$datos["noClasif"] = $this->noClasif;
		$datos["noItem"] = $this->noItem;
		$datos["noEdicion"] = $this->noEdicion;
		$datos["idSubDivGeo"] = $this->idSubDivGeo;
		$datos["dimension"] = $this->dimension;
		$datos["serie"] = $this->serie;
		$datos["asientoPrin"] = $this->asientoPrin;
		$datos["volumen"] = $this->volumen;
		$datos["idIdioma"] = $this->idIdioma;
		
		return $datos;
		
	}

}


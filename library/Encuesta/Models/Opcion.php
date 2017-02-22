<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Models_Opcion
{
	private $idOpcionCategoria;

    public function getIdOpcionCategoria() {
        return $this->idOpcion;
    }
    
    public function setIdOpcionCategoria($idOpcionCategoria) {
        $this->idOpcionCategoria = $idOpcionCategoria;
    }

    private $idCategoriasRespuesta;

    public function getIdCategoriasRespuesta() {
        return $this->idCategoriasRespuesta;
    }
    
    public function setIdCategoriasRespuesta($idCategoriasRespuesta) {
        $this->idCategoriasRespuesta = $idCategoriasRespuesta;
    }

    private $nombreOpcion;

    public function getNombreOpcion() {
        return $this->nombreOpcion;
    }
    
    public function setNombreOpcion($nombreOpcion) {
        $this->nombreOpcion = $nombreOpcion;
    }
	
	private $tipoValor;
	
	public function getTipoValor() {
		return $this->tipoValor;
	}
	
	public function setTipoValor($tipoValor) {
		$this->tipoValor = $tipoValor;
	}
	
	private $valorEntero;
	
	public function getValorEntero() {
		return $this->valorEntero;
	}
	
	public function setValorEntero($valorEntero) {
		$this->valorEntero = $valorEntero;
	}
	
	private $valorTexto;
	
	public function getValorTexto() {
		return $this->valorTexto;
	}
	
	public function setValorTexto($valorTexto) {
		$this->valorTexto = $valorTexto;
	}
	
	private $valorDecimal;
	
	public function getValorDecimal() {
		return $this->valorDecimal;
	}
	
	public function setValorDecimal($valorDecimal) {
		$this->valorDecimal = $valorDecimal;
	}
    
    private $orden;

    public function getOrden() {
        return $this->orden;
    }
    
    public function setOrden($orden) {
        $this->orden = $orden;
    }
	
	private $fecha;

    public function getFecha() {
        return $this->fecha;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
	
    public function __construct(array $datos) 
    {
    	if(array_key_exists("idOpcionCategoria", $datos)) $this->idOpcionCategoria = $datos["idOpcionCategoria"];
		if(array_key_exists("idCategoriasRespuesta", $datos)) $this->idCategoriasRespuesta = $datos["idCategoriasRespuesta"];
		$this->nombreOpcion = $datos["nombreOpcion"];
		$this->tipoValor = $datos["tipoValor"];
		if(array_key_exists("valorEntero", $datos)) $this->valorEntero = $datos["valorEntero"];
		if(array_key_exists("valorTexto", $datos)) $this->valorTexto = $datos["valorTexto"];
		if(array_key_exists("valorDecimal", $datos)) $this->valorDecimal = $datos["valorDecimal"];
		if(array_key_exists("orden", $datos)) $this->orden = $datos["orden"];
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
	}
	
	public function toArray() {
		$datos = array();
		
		$datos["idOpcionCategoria"] = $this->idOpcionCategoria;
		$datos["idCategoriasRespuesta"] = $this->idCategoriasRespuesta;
		$datos["nombreOpcion"] = $this->nombreOpcion;
		$datos["tipoValor"] = $this->tipoValor;
		$datos["valorEntero"] = $this->valorEntero;
		$datos["valorTexto"] = $this->valorTexto;
		$datos["valorDecimal"] = $this->valorDecimal;
		$datos["orden"] = $this->orden;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


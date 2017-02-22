<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Models_Pregunta
{
	private $idPregunta;

    public function getIdPregunta() {
        return $this->idPregunta;
    }
    
    public function setIdPregunta($idPregunta) {
        $this->idPregunta = $idPregunta;
    }
	
	private $idEncuesta;

    public function getIdEncuesta() {
        return $this->idEncuesta;
    }
    
    public function setIdEncuesta($idEncuesta) {
        $this->idEncuesta = $idEncuesta;
    }

    private $nombre;

    public function getNombre() {
        return $this->nombre;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    private $origen;

    public function getOrigen() {
        return $this->origen;
    }
    
    public function setOrigen($origen) {
        $this->origen = $origen;
    }

    private $idOrigen;

    public function getIdOrigen() {
        return $this->idOrigen;
    }
    
    public function setIdOrigen($idOrigen) {
        $this->idOrigen = $idOrigen;
    }

    private $tipo;

    public function getTipo() {
        return $this->tipo;
    }
    
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
	
	private $opciones;

    public function getOpciones() {
        return $this->opciones;
    }
    
    public function setOpciones($opciones) {
        $this->opciones = $opciones;
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
    	if(array_key_exists("idPregunta", $datos)) $this->idPregunta = $datos["idPregunta"];
		if(array_key_exists("idEncuesta", $datos)) $this->idEncuesta = $datos["idEncuesta"];
		$this->nombre = $datos["nombre"];
		$this->origen = $datos["origen"];
		$this->idOrigen = $datos["idOrigen"];
		$this->tipo = $datos["tipo"];
		if(array_key_exists("opciones", $datos)) $this->opciones = $datos["opciones"];
		if(array_key_exists("orden", $datos)) $this->orden = $datos["orden"];
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idPregunta"] = $this->idPregunta;
		$datos["idEncuesta"] = $this->idEncuesta;
		$datos["nombre"] = $this->nombre;
		$datos["origen"] = $this->origen;
		$datos["idOrigen"] = $this->idOrigen;
		$datos["tipo"] = $this->tipo;
		$datos["opciones"] = $this->opciones;
		$datos["orden"] = $this->orden;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Models_Seccion
{
	private $idSeccionEncuesta;

    public function getIdSeccionEncuesta() {
        return $this->idSeccionEncuesta;
    }
    
    public function setIdSeccionEncuesta($idSeccionEncuesta) {
        $this->idSeccionEncuesta = $idSeccionEncuesta;
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

    private $orden;

    public function getOrden() {
        return $this->orden;
    }
    
    public function setOrden($orden) {
        $this->orden = $orden;
    }

    private $elementos;

    public function getElementos() {
        return $this->elementos;
    }
    
    public function setElementos($elementos) {
        $this->elementos = $elementos;
    }
	
	private $fecha;

    public function getFecha() {
        return $this->fecha;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
	
    public function __construct(array $datos) {
		
		if(array_key_exists("idSeccionEncuesta", $datos)) $this->idSeccionEncuesta = $datos["idSeccionEncuesta"];
		if(array_key_exists("idEncuesta", $datos)) $this->idEncuesta = $datos["idEncuesta"];
		$this->nombre = $datos["nombre"];
		if(array_key_exists("orden", $datos)) $this->orden = $datos["orden"];
		if(array_key_exists("elementos", $datos)) $this->elementos = $datos["elementos"];
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
	}
	
	public function toArray() {
		$datos = array();
		
		$datos["idSeccionEncuesta"] = $this->idSeccion;
		$datos["idEncuesta"] = $this->idEncuesta;
		$datos["nombre"] = $this->nombre;
		$datos["orden"] = $this->orden;
		$datos["elementos"] = $this->elementos;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


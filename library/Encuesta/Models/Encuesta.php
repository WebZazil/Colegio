<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Models_Encuesta
{
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
	
	private $nombreClave;

    public function getNombreClave() {
        return $this->nombreClave;
    }
    
    public function setNombreClave($nombreClave) {
        $this->nombreClave = $nombreClave;
    }
	
	private $estatus;

    public function getEstatus() {
        return $this->estatus;
    }
    
    public function setEstatus($estatus) {
        $this->estatus = $estatus;
    }

    private $descripcion;

    public function getDescripcion() {
        return $this->descripcion;
    }
    
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
    private $fecha;

    public function getFecha() {
        return $this->fecha;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
	
    public function __construct(array $datos) {
		
		if(array_key_exists("idEncuesta", $datos)) $this->idEncuesta = $datos["idEncuesta"];
		$this->nombre = $datos["nombre"];
		$this->nombreClave = $datos["nombreClave"];
        if(array_key_exists("estatus", $datos)) $this->estatus = $datos["estatus"];
        $this->descripcion = $datos["descripcion"];
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
		
	}
	
	public function toArray() {
		
		$datos = array();
		
		$datos["idEncuesta"] = $this->idEncuesta;
		$datos["nombre"] = $this->nombre;
		$datos["nombreClave"] = $this->nombreClave;
		$datos["descripcion"] = $this->descripcion;
		$datos["estatus"] = $this->estatus;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


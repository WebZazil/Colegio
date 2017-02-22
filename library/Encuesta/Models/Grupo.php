<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Models_Grupo
{
	private $idGrupoSeccion;

    public function getIdGrupoSeccion() {
        return $this->idGrupoSeccion;
    }
    
    public function setIdGrupoSeccion($idGrupoSeccion) {
        $this->idGrupoSeccion = $idGrupoSeccion;
    }
	
	private $idSeccionEncuesta;

    public function getIdSeccionEncuesta() {
        return $this->idSeccionEncuesta;
    }
    
    public function setIdSeccionEncuesta($idSeccionEncuesta) {
        $this->idSeccionEncuesta = $idSeccionEncuesta;
    }

    private $nombre;

    public function getNombre() {
        return $this->nombre;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
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
		
		if(array_key_exists("idGrupoSeccion", $datos)) $this->idGrupoSeccion = $datos["idGrupoSeccion"];
		if(array_key_exists("idSeccionEncuesta", $datos)) $this->idSeccionEncuesta = $datos["idSeccionEncuesta"];
		$this->nombre = $datos["nombre"];
		$this->tipo = $datos["tipo"];
		if(array_key_exists("opciones", $datos)) $this->opciones = $datos["opciones"];
		if(array_key_exists("orden", $datos)) $this->orden = $datos["orden"];
		if(array_key_exists("elementos", $datos)) $this->elementos = $datos["elementos"];
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idGrupoSeccion"] = $this->idGrupoSeccion;
		$datos["idSeccionEncuesta"] = $this->idSeccionEncuesta;
		$datos["nombre"] = $this->nombre;
		$datos["tipo"] = $this->tipo;
		$datos["opciones"] = $this->opciones;
		$datos["orden"] = $this->orden;
		$datos["elementos"] = $this->elementos;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


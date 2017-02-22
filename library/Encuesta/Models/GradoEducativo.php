<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Models_GradoEducativo {
	
	private $idGradoEducativo;

    public function getIdGradoEducativo() {
        return $this->idGradoEducativo;
    }
    
    public function setIdGradoEducativo($idGradoEducativo) {
        $this->idGrado = $idGradoEducativo;
    }

    private $idNivelEducativo;

    public function getIdNivelEducativo() {
        return $this->idNivelEducativo;
    }
    
    public function setIdNivelEducativo($idNivelEducativo) {
        $this->idNivelEducativo = $idNivelEducativo;
    }

    private $gradoEducativo;

    public function getGradoEducativo() {
        return $this->gradoEducativo;
    }
    
    public function setGradoEducativo($gradoEducativo) {
        $this->gradoEducativo = $gradoEducativo;
    }

    private $abreviatura;

    public function getAbreviatura() {
        return $this->abreviatura;
    }
    
    public function setAbreviatura($abreviatura) {
        $this->abreviatura = $abreviatura;
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

    public function __construct(array $datos)
    {
        if(array_key_exists("idGradoEducativo", $datos)) $this->idGradoEducativo = $datos["idGradoEducativo"];
		if(array_key_exists("idNivelEducativo", $datos)) $this->idNivelEducativo = $datos["idNivelEducativo"];
		$this->gradoEducativo = $datos["gradoEducativo"];
		$this->abreviatura = $datos["abreviatura"];
		$this->descripcion = $datos["descripcion"];
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
    }
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idGradoEducativo"] = $this->idGradoEducativo;
		$datos["idNivelEducativo"] = $this->idNivelEducativo;
		$datos["gradoEducativo"] = utf8_encode($this->gradoEducativo);
		$datos["abreviatura"] = utf8_encode($this->abreviatura);
		$datos["descripcion"] = utf8_encode($this->descripcion);
		$datos["fecha"] = utf8_encode($this->fecha);
		
		return $datos;
	}
}


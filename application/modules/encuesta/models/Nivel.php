<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Model_Nivel
{
	private $idNivel;

    public function getIdNivel() {
        return $this->idNivel;
    }
    
    public function setIdNivel($idNivel) {
        $this->idNivel = $idNivel;
    }

    private $nivel;

    public function getNivel() {
        return $this->nivel;
    }
    
    public function setNivel($nivel) {
        $this->nivel = $nivel;
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
		if(array_key_exists("idNivelEducativo", $datos)) $this->idNivel = $datos["idNivelEducativo"];
		$this->nivel = $datos["nivelEducativo"];
		$this->descripcion = $datos["descripcion"];
		$this->fecha = $datos["fecha"];
    }
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idNivelEducativo"] = $this->idNivel;
		$datos["nivelEducativo"] = utf8_encode($this->nivel);
		$datos["descripcion"] = utf8_encode($this->descripcion);
		$datos["fecha"] = utf8_encode($this->fecha);
		
		return $datos;
	}

}


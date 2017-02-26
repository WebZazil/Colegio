<?php
/**
 * 
 */
class Encuesta_Model_Materia
{
	private $idMateriaEscolar;

    public function getIdMateriaEscolar() {
        return $this->idMateriaEscolar;
    }
    
    public function setIdMateriaEscolar($idMateriaEscolar) {
        $this->idMateriaEscolar = $idMateriaEscolar;
    }

    private $idCicloEscolar;

    public function getIdCicloEscolar() {
        return $this->idCicloEscolar;
    }
    
    public function setIdCicloEscolar($idCicloEscolar) {
        $this->idCicloEscolar = $idCicloEscolar;
    }

    private $idGradoEducativo;

    public function getIdGradoEducativo() {
        return $this->idGradoEducativo;
    }
    
    public function setIdGradoEducativo($idGradoEducativo) {
        $this->idGradoEducativo = $idGradoEducativo;
    }

    private $materiaEscolar;

    public function getMateriaEscolar() {
        return $this->materiaEscolar;
    }
    
    public function setMateriaEscolar($materiaEscolar) {
        $this->materiaEscolar = $materiaEscolar;
    }

    private $creditos;

    public function getCreditos() {
        return $this->creditos;
    }
    
    public function setCreditos($creditos) {
        $this->creditos = $creditos;
    }
	
	private $fecha;
	
	public function getFecha() {
		return $fecha;
	}
	
	public function setFecha($fecha) {
		$this->fecha = $fecha;
	}
	
    public function __construct(array $datos) {
		if(array_key_exists("idMateriaEscolar", $datos)) $this->idMateriaEscolar = $datos["idMateriaEscolar"];
		if(array_key_exists("idCicloEscolar", $datos)) $this->idCicloEscolar = $datos["idCicloEscolar"];
		if(array_key_exists("idGradoEducativo", $datos)) $this->idGradoEducativo = $datos["idGradoEducativo"];
		$this->materiaEscolar = utf8_encode($datos["materiaEscolar"]);
		$this->creditos = utf8_encode($datos["creditos"]);
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idMateriaEscolar"] = $this->idMateriaEscolar;
		$datos["idCicloEscolar"] = $this->idCicloEscolar;
		$datos["idGradoEducativo"] = $this->idGradoEducativo;
		$datos["materiaEscolar"] = $this->materiaEscolar;
		$datos["creditos"] = $this->creditos;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


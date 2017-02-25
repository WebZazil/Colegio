<?php
/**
 * 
 */
class Encuesta_Model_Materia
{
	private $idMateria;

    public function getIdMateria() {
        return $this->idMateria;
    }
    
    public function setIdMateria($idMateria) {
        $this->idMateria = $idMateria;
    }

    private $idCiclo;

    public function getIdCiclo() {
        return $this->idCiclo;
    }
    
    public function setIdCiclo($idCiclo) {
        $this->idCiclo = $idCiclo;
    }

    private $idGrado;

    public function getIdGrado() {
        return $this->idGrado;
    }
    
    public function setIdGrado($idGrado) {
        $this->idGrado = $idGrado;
    }

    private $materia;

    public function getMateria() {
        return $this->materia;
    }
    
    public function setMateria($materia) {
        $this->materia = $materia;
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
		if(array_key_exists("idMateriaEscolar", $datos)) $this->idMateria = $datos["idMateriaEscolar"];
		if(array_key_exists("idCicloEscolar", $datos)) $this->idCiclo = $datos["idCicloEscolar"];
		if(array_key_exists("idGradoEducativo", $datos)) $this->idGrado = $datos["idGradoEducativo"];
		$this->materia = utf8_encode($datos["materiaEscolar"]);
		$this->creditos = utf8_encode($datos["creditos"]);
		$this->fecha = utf8_encode($datos["fecha"]);
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idMateriaEscolar"] = $this->idMateria;
		$datos["idCicloEscolar"] = $this->idCiclo;
		$datos["idGradoEducativo"] = $this->idGrado;
		$datos["materiaEscolar"] = $this->materia;
		$datos["creditos"] = $this->creditos;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


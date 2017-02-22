<?php

class Encuesta_Models_Ciclo
{
	private $idCiclo;

    public function getIdCiclo() {
        return $this->idCiclo;
    }
    
    public function setIdCiclo($idCiclo) {
        $this->idCiclo = $idCiclo;
    }
	
	private $idPlan;

    public function getIdPlan() {
        return $this->idPlan;
    }
    
    public function setIdPlan($idPlan) {
        $this->idPlan = $idPlan;
    }
    
    private $ciclo;

    public function getCiclo() {
        return $this->ciclo;
    }
    
    public function setCiclo($ciclo) {
        $this->ciclo = $ciclo;
    }
	
	private $vigente;

    public function getVigente() {
        return $this->vigente;
    }
    
    public function setVigente($vigente) {
        $this->vigente = $vigente;
    }

    private $inicio;

    public function getInicio() {
        return $this->inicio;
    }
    
    public function setInicio($inicio) {
        $this->inicio = $inicio;
    }

    private $termino;

    public function getTermino() {
        return $this->termino;
    }
    
    public function setTermino($termino) {
        $this->termino = $termino;
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
		if(array_key_exists("idCicloEscolar", $datos)) $this->idCiclo = $datos["idCicloEscolar"];
		if(array_key_exists("idPlanEducativo", $datos)) $this->idPlan = $datos["idPlanEducativo"];
		$this->ciclo = $datos["ciclo"];
		$this->vigente = $datos["vigente"];
		$this->inicio = $datos["inicio"];
		$this->termino = $datos["termino"];
		$this->descripcion = $datos["descripcion"];
		if(array_key_exists("fecha", $datos)) $this->fecha = $datos["fecha"];
	}
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idCicloEscolar"] = $this->idCiclo;
		$datos["idPlanEducativo"] = $this->idPlan;
		$datos["ciclo"] = $this->ciclo;
		$datos["vigente"] = $this->vigente;
		$datos["inicio"] = $this->inicio;
		$datos["termino"] = $this->termino;
		$datos["descripcion"] = $this->descripcion;
		$datos["fecha"] = $this->fecha;
		
		return $datos;
	}
}


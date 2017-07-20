<?php

class Biblioteca_Model_Materia
{
	
		private $idMateria;
	
	public function getIdMateria(){
		return $this->idMateria;
	}
	
	public function setIdMateria($idMateria){
		$this->idMateria = $idMateria;
	}
	
	private $materia;

    public function getMateria() {
        return $this->materia;
    }
    
    public function setMateria($materia) {
        $this->materia = $materia;
    }

    
	private $descripcion;
	
	public function getDescripcion(){
		return $this->descripcion;
	}
	
	public function setDescripcion($descripcion){
		$this->descripcion = $descripcion;
	}
	
	
	public function __construct($datos){
		if(array_key_exists("idMateria", $datos)) $this->idMateria = $datos["idMateria"]; // línea que hace referencia a la llave primaria en la base de datos auto incrementable
		$this->materia = $datos["materia"];
		$this->descripcion = $datos["descripcion"];
	}
	
	public function toArray(){
		$datos = array();
		
		$datos["idMateria"] = $this->idMateria;
		$datos["materia"] = $this->materia;
		$datos["descripcion"] = $this->descripcion;
		
		return $datos;
		
	}


}


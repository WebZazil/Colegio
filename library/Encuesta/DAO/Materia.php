<?php
/**
 * 
 */
class Encuesta_DAO_Materia implements Encuesta_Interfaces_IMateria {
	
	private $tablaMateria;
	private $tablaGrado;
	private $tablaCiclo;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaMateria = new Encuesta_Model_DbTable_MateriaEscolar(array('db'=>$dbAdapter));
		$this->tablaCiclo = new Encuesta_Model_DbTable_CicloEscolar(array('db'=>$dbAdapter));
		$this->tablaGrado = new Encuesta_Model_DbTable_GradoEducativo(array('db'=>$dbAdapter));
		//$this->tablaMateria->setDefaultAdapter($dbAdapter);
	}
	
	public function getMateriaById($idMateria) {
		$tablaMateria = $this->tablaMateria;
		$select = $tablaMateria->select()->from($tablaMateria)->where("idMateriaEscolar = ?",$idMateria);
		$rowMateria = $tablaMateria->fetchRow($select);
		
		if(is_null($rowMateria)){
			return null;
		}else{
			//$modelMateria = new Encuesta_Model_Materia($rowMateria->toArray());
			return $rowMateria->toArray();
		}
	}
	
	public function getMateriasByIdGradoAndCurrentCiclo($idGrado) {
		//Obtenemos ciclo actual
		$tablaCiclo = $this->tablaCiclo;
		$ciclos = $tablaCiclo->fetchAll();
		$cicloActual = null;
		
		foreach ($ciclos as $ciclo) {
			if ($ciclo->vigente) {
				$cicloActual = $ciclo->toArray();
			}
		}
		
		$tablaMaterias = $this->tablaMateria;
		$select = $tablaMaterias->select()->from($tablaMaterias)->where("idCicloEscolar=?",$cicloActual["idCicloEscolar"])->where("idGradoEducativo=?",$idGrado);
		
		$materias = $tablaMaterias->fetchAll($select);
		if (is_null($materias)) {
			return null;
		} else {
			return $materias->toArray();
		}
		
	}
	
	public function obtenerMateria($idMateria) {
		$tablaMateria = $this->tablaMateria;
		$select = $tablaMateria->select()->from($tablaMateria)->where("idMateriaEscolar = ?",$idMateria);
		$rowMateria = $tablaMateria->fetchRow($select);
		
		if(is_null($rowMateria)){
			return null;
		}else{
			$modelMateria = new Encuesta_Model_Materia($rowMateria->toArray());
			return $modelMateria;
		}
	}
	
	public function obtenerMaterias($idCiclo,$idGrado){
		$tablaMateria = $this->tablaMateria;
		$select = $tablaMateria->select()->from($tablaMateria)->where("idCicloEscolar=?",$idCiclo)->where("idGradoEducativo=?",$idGrado);
		$rowsMaterias = $tablaMateria->fetchAll($select);
		$modelMaterias = array();
		foreach ($rowsMaterias as $row) {
			$modelMateria = new Encuesta_Model_Materia($row->toArray());
			$modelMaterias[] = $modelMateria;
		}
		
		return $modelMaterias;
	}
	
	public function obtenerMateriasGrado($idGrado){
		$tablaMateria = $this->tablaMateria;
		$select = $tablaMateria->select()->from($tablaMateria)->where("idGradoEducativo=?",$idGrado);
		$rowsMaterias = $tablaMateria->fetchAll($select);
		
		return $rowsMaterias->toArray();
	}
	
	public function obtenerMateriasGrupo($idCiclo,$idGrado){
		$tablaMateria = $this->tablaMateria;
		$select = $tablaMateria->select()->from($tablaMateria)->where("idCicloEscolar=?",$idCiclo)->where("idGradoEducativo=?",$idGrado);
		$rowsMaterias = $tablaMateria->fetchAll($select);
		$modelMaterias = array();
		foreach ($rowsMaterias as $row) {
			$modelMateria = new Encuesta_Model_Materia($row->toArray());
			$modelMaterias[] = $modelMateria;
		}
		return $modelMaterias;
	}
	
	public function crearMateria(Encuesta_Model_Materia $materia){
		$tablaMateria = $this->tablaMateria;
		$materia->setFecha(date('Y-m-d h:i:s',time()));
		
		$tablaMateria->insert($materia->toArray());
		/*
		try{
			$tablaMateria->insert($materia->toArray());
		}catch(Exception $ex){
			throw new Util_Exception_BussinessException("Error: <strong>". $ex->getMessage() . "</strong>");
		}*/
		
		
	}
	
	public function editarMateria($idMateria, array $materia){
		$tablaMateria = $this->tablaMateria;
		$where = $tablaMateria->getAdapter()->quoteInto("idMateriaEscolar = ?", $idMateria);
		$tablaMateria->update($materia, $where);
	}
}

<?php
/**
 * 
 */
class Encuesta_DAO_Materia implements Encuesta_Interfaces_IMateria {
	
	private $tablaMateria;
	private $tablaGrado;
	private $tablaCiclo;
	
	public function __construct($dbAdapter) {
	    $config = array('db'=>$dbAdapter);
		
		$this->tablaMateria = new Encuesta_Data_DbTable_MateriaEscolar($config);
		$this->tablaCiclo = new Encuesta_Data_DbTable_CicloEscolar($config);
		$this->tablaGrado = new Encuesta_Data_DbTable_GradoEducativo($config);
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
			//print_r("Id Grado: <strong>".$idGrado."</strong>");
			//print_r("Materias del ciclo escolar id:<strong>".$cicloActual["idCicloEscolar"]."</strong>");
			//print_r($materias->toArray());
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
			//$modelMateria = new Encuesta_Model_Materia($rowMateria->toArray());
			return $rowMateria->toArray();
		}
	}
	
	public function obtenerMaterias($idCiclo,$idGrado){
		$tablaMateria = $this->tablaMateria;
		$select = $tablaMateria->select()->from($tablaMateria)->where("idCicloEscolar=?",$idCiclo)->where("idGradoEducativo=?",$idGrado);
		$rowsMaterias = $tablaMateria->fetchAll($select);
		
		return $rowsMaterias->toArray();
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
		
		return $rowsMaterias->toArray();
	}
	
	public function crearMateria(array $materia){
		$tablaMateria = $this->tablaMateria;
		$materia['fecha'] = date('Y-m-d h:i:s',time());
		
		return $tablaMateria->insert($materia);
	}
	
	public function editarMateria($idMateria, array $materia){
		$tablaMateria = $this->tablaMateria;
		$where = $tablaMateria->getAdapter()->quoteInto("idMateriaEscolar = ?", $idMateria);
		$tablaMateria->update($materia, $where);
	}
}

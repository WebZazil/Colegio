<?php
/**
 * Clase que opera sobre materias en la biblioteca
 * @author Alizon Fernanda Díaz
 * @copyright 2016
 * @version 1.0.0
 */
class Biblioteca_DAO_Materia implements Biblioteca_Interfaces_IMateria {
		
	private $tablaLibros;
	private $tablaMateria;
	private $tablaLibrosMateria;
	
	function __construct() {
		//$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$dbAdapter = Zend_Registry::get("dbgenerale");
		
		$this->tablaLibros = new Biblioteca_Model_DbTable_Libro(array('db'=>$dbAdapter));
		$this->tablaMateria = new Biblioteca_Model_DbTable_Materia(array('db'=>$dbAdapter));
		$this->tablaLibrosMateria = new Biblioteca_Model_DbTable_LibrosMateria(array('db'=>$dbAdapter));
	}
	
	/**
	 * Función que agrega una materia al catalogo de libros
	 * @param $materia materia a agregar
	 */
	public function agregarMateria(Biblioteca_Model_Materia $materia){//firma de la función
		
		$tablaMateria = $this->tablaMateria;
		$tablaMateria->insert($materia->toArray());
		
	}
	
	/**
	 * Función que obtiene un registro de la tabla materia, mediante el idMateria
	 * @param $idMateria
	 * @return array | null
	 */
	
	public function obtenerMateriaB($idMateria)
	{
		$tablaMateria = $this->tablaMateria;
		$rowMaterias = $tablaMateria->fetchAll();
		
		$modelMaterias = array();
		
		foreach ($rowMaterias as $rowMateria) {
			$modelMateria = new Biblioteca_Model_Materia($rowMateria->toArray());
			$modelMateria->setIdMateria($rowMateria->idMateria);
			
			$modelMaterias[] = $modelMateria;
		}
		
		return $modelMaterias;
	}
	
	public function obtenerMateriasB(Biblioteca_Model_Materia $materia)
	{
		$tablaMateria = $this->tablaMateria;
		$tablaMateria->select($materia->toArray());
	}
	
	public function getAllMaterias(){
		$tablaMateria = $this->tablaMateria;
		$rowsMaterias = $tablaMateria->fetchAll();
		
		if(!is_null($rowsMaterias)){
			return $rowsMaterias->toArray();
		}else{
			return array();
		}
	}
	
	/**
	 * 
	 */
	public function getLibrosByIdMateria($idMateria){
		$tablaLM = $this->tablaLibrosMateria;
		$tablaLibros = $this->tablaLibros;
		$select = $tablaLM->select()->from($tablaLM)->where("idMateria=?", $idMateria);
		$rowLM = $tablaLM->fetchRow($select);
		
		if(!is_null($rowLM)){
			//idsLibro
			$idsLibros = explode(",", $rowLM->idsLibro);
			$select = $tablaLibros->select()->from($tablaLibros)->where("idLibro IN (?)", $idsLibros);
			$rowLibros = $tablaLibros->fetchAll($select);
			if(!is_null($rowLibros)){
				$arrModelLibros = array();
				foreach ($rowLibros as $rowLibro) {
					$model = new Biblioteca_Model_Libro($rowLibro->toArray());
					$arrModelLibros[] = $model;
				}
				
				return $arrModelLibros;
				
				$db = $this->tablaLibros->getAdapter();
				$libros = $db->query($select)->fetchAll();
				
				$arrModelLibros = $libros;
				
				echo Zend_Json::encode($libros);
				
			}else{
				throw new Exception("Error: No existen libros relacionados con la materia", 1);
			}
		}else{
			throw new Exception("Materia con Id: ". $idMateria. " no existe.", 1);
		}
	}

}

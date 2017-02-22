<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Grado implements Encuesta_Interfaces_IGrado {
	
	private $tablaGradoEducativo;
	//private $tablaNivelEducativo;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaGradoEducativo = new Encuesta_Model_DbTable_GradoEducativo(array('db'=>$dbAdapter));
		//$this->tablaNivelEducativo = new Encuesta_Model_DbTable_NivelEducativo(array('db'=>$dbAdapter));
	}
	/*
	public function obtenerGrado($idGrado){
		$tablaGrado = $this->tablaGradoEducativo;
		$select = $tablaGrado->select()->from($tablaGrado)->where("idGradoEducativo = ?",$idGrado);
		$rowGrado = $tablaGrado->fetchRow($select);
		
		return $rowGrado->toArray();
	}
	
	public function obtenerGrados($idNivel){
		$tablaGrado = $this->tablaGradoEducativo;
		$select = $tablaGrado->select()->from($tablaGrado)->where("idNivelEducativo = ?",$idNivel);
		$rowsGrados = $tablaGrado->fetchAll($select);
		
		return $rowsGrados->toArray();
	}
	
	public function crearGrado(array $grado){
		$tablaGrado = $this->tablaGradoEducativo;
		
		$tablaGrado->insert($grado);
	}
	
	public function editarGrado($idGrado, array $datos){
		$tablaGrado = $this->tablaGradoEducativo;
		$where = $tablaGrado->getAdapter()->quoteInto("idGrado=?", $idGrado);
		$tablaGrado->update($datos, $where);
	}
	
	public function eliminarGrado($idGrado){
		$tablaGrado = $this->tablaGradoEducativo;
		
	}
	*/
	// **************************************************** Implementando Standard de nombres.
	
	/**
	 * 
	 */
	public function getGradoById($id){
		$tablaGrado = $this->tablaGradoEducativo;
		$select = $tablaGrado->select()->from($tablaGrado)->where("idGradoEducativo = ?", $id);
		$rowGrado = $tablaGrado->fetchRow($select);
		
		$modelGrado = new Encuesta_Models_GradoEducativo($rowGrado->toArray());
		
		return $modelGrado;
	}
	
	/**
	 * 
	 */
	public function getGradosByIdNivel($idNivel){
		$tablaGrado = $this->tablaGradoEducativo;
		$select = $tablaGrado->select()->from($tablaGrado)->where("idNivelEducativo = ?",$idNivel);
		$rowsGrados = $tablaGrado->fetchAll($select);
		
		$modelsGrados = array();
		
		foreach ($rowsGrados as $row) {
			$model = new Encuesta_Models_GradoEducativo($row->toArray());
			$modelsGrados[] = $model;
		}
		
		return $modelsGrados;
	}
	
	/**
	 * 
	 */
	public function addGrado(Encuesta_Models_GradoEducativo $grado){
		$tablaGrado = $this->tablaGradoEducativo;
		$datos = $grado->toArray();
		unset($datos["fecha"]);
		
		$tablaGrado->insert($datos);
	}
	
	/**
	 * 
	 */
	public function editGrado($idGrado, array $datos){
		$tablaGrado = $this->tablaGradoEducativo;
		$where = $tablaGrado->getAdapter()->quoteInto("idGrado=?", $idGrado);
		$tablaGrado->update($datos, $where);
	}
	
	/**
	 * 
	 */
	public function dropGradoById($id){
		//@TODO verificar dependencias, borrar registros de otras tablas que dependan de este registro!!
		$tablaGrado = $this->tablaGradoEducativo;
	}
}

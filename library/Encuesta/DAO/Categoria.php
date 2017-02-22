<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Categoria implements Encuesta_Interfaces_ICategoria {
	
	private $tablaCategoria;
	private $tablaOpcion;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaCategoria = new Encuesta_Model_DbTable_CategoriasRespuesta(array('db'=>$dbAdapter));
		$this->tablaOpcion = new Encuesta_Model_DbTable_OpcionCategoria(array('db'=>$dbAdapter));
		
	}
	
	// =====================================================================================>>>   Buscar
	public function obtenerCategoria($idCategoria){
		$tablaCategoria = $this->tablaCategoria;
		$select = $tablaCategoria->select()->from($tablaCategoria)->where("idCategoriasRespuesta = ?", $idCategoria);
		$rowCategoria = $tablaCategoria->fetchRow($select);
		
		//$modelCategoria = new Encuesta_Model_Categoria($rowCategoria->toArray());
		
		return $rowCategoria->toArray();
	}

	public function obtenerOpciones($idCategoria){
		$tablaOpcion = $this->tablaOpcion;
		$select = $tablaOpcion->select()->from($tablaOpcion)->where("idCategoriasRespuesta = ?", $idCategoria);
		$rowOpciones = $tablaOpcion->fetchAll($select);
		
		/*
		$opcionesModel = array();
		foreach ($rowOpciones as $rowOpcion) {
			$opcionModel = new Encuesta_Model_Opcion($rowOpcion->toArray());
			
			$opcionesModel[] = $opcionModel;
		}*/
		
		return $rowOpciones->toArray();
	}
	
	public function obtenerCategorias() {
		$tablaCategoria = $this->tablaCategoria;
		$rowCategorias = $tablaCategoria->fetchAll();
		/*
		$modelCategorias = array();
		
		foreach ($rowCategorias as $rowCategoria) {
			$modelCategoria = new Encuesta_Model_Categoria($rowCategoria->toArray());
			
			$modelCategorias[] = $modelCategoria;
		}
		*/
		return $rowCategorias->toArray();
	}
	
	// =====================================================================================>>>   Insertar
	public function crearCategoria(array $categoria){
		$tablaCategoria = $this->tablaCategoria; 
		$tablaCategoria->insert($categoria);
	}
	// =====================================================================================>>>   Actualizar
	public function editarCategoria($idCategoria, array $categoria) {
		$tablaCategoria = $this->tablaCategoria;
		$where = $tablaCategoria->getAdapter()->quoteInto("idCategoriasRespuesta = ?", $idCategoria);
		
		$tablaCategoria->update($categoria->toArray(), $where);
	}
	// =====================================================================================>>>   Eliminar
	public function eliminarCategoria($idCategoria){
		$tablaCategoria = $this->tablaCategoria;
		$where = $tablaCategoria->getAdapter()->quoteInto("idCategoriasRespuesta = ?", $idCategoria);
		
		$tablaCategoria->delete($where);
	}
	
	public function eliminarOpciones($idCategoria){
		$tablaOpcion = $this->tablaOpcion;
		$select = $tablaOpcion->select()->where("idCategoriasRespuesta = ?", $idCategoria);
		
		$tablaOpcion->delete($select);
	}
}

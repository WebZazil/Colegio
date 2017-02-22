<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
 
 
class Sistema_DAO_Estado implements Sistema_Interfaces_IEstado {
	private $tablaEstado;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaEstado = new Sistema_Model_DbTable_Estado(array('db'=>$dbAdapter));
	}
	
	public function obtenerEstado($idEstado){
		$tablaEstado = $this->tablaEstado;
		$select = $tablaEstado->select()->from($tablaEstado)->where("idEstado = ?", $idEstado);
		$rowEstado = $tablaEstado->fetchRow($select);
		
		$estadoModel = new Sistema_Model_Estado($rowEstado->toArray());
		$estadoModel->setIdEstado($rowEstado->idEstado);
		
		return $estadoModel;
	}
	
	public function obtenerEstados(){
		$tablaEstado = $this->tablaEstado;
		$rowEstados = $tablaEstado->fetchAll();
		
		$modelEstados = array();
		
		foreach ($rowEstados as $rowEstado) {
			$modelEstado = new Sistema_Model_Estado($rowEstado->toArray());
			$modelEstado->setIdEstado($rowEstado->idEstado);
			
			$modelEstados[] = $modelEstado;
		}
		
		return $modelEstados;
	}
	
	public function obtenerMunicipios($idEstado){}
	
	public function crearEstado(Sistema_Model_Estado $estado){
		$tablaEstado = $this->tablaEstado;
		$tablaEstado->insert($estado->toArray());
	}
	
	public function editarEstado($idEstado, Sistema_Model_Estado $estado){
		$tablaEstado = $this->tablaEstado;
		$where = $tablaEstado->getAdapter()->quoteInto("idEstado = ?", $idEstado);
		//$select = $tablaEstado->select()->from($tablaEstado)->where("idEstado = ?", $idEstado);
		
		$tablaEstado->update($estado->toArray(), $where);
	}
	
	public function eliminarEstado($idEstado){
		$tablaEstado = $this->tablaEstado;
		$where = $tablaEstado->getAdapter()->quoteInto("idEstado = ?", $idEstado);
		//$select = $tablaEstado->select()->from($tablaEstado)->where("idEstado = ?", $idEstado);
		
		$tablaEstado->delete($where);
	}
}

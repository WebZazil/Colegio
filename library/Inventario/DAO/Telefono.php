<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Inventario_DAO_Telefono implements Inventario_Interfaces_ITelefono {
	private $tablaTelefono;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaTelefono = new Application_Model_DbTable_Telefono(array('db'=>$dbAdapter));
	}
	
	public function obtenerTelefono($idTelefono){
		$tablaTelefono = $this->tablaTelefono;
		$select = $tablaTelefono->select()->from($tablaTelefono)->where("idTelefono = ?", $idTelefono);
		$rowTelefono = $tablaTelefono->fetchRow($select);
	
		$telefonoModel = new Application_Model_Telefono($rowTelefono->toArray());
		//$telefonoModel->setIdTelefono($rowTelefono->idTelefono);
		
		return $telefonoModel;
	}
	public function obtenerTelefonos(){
		$tablaTelefono = $this->tablaTelefono;
		$rowTelefonos = $tablaTelefono->fetchAll();
		
		$modelTelefonos = array();
		
		foreach ($rowTelefonos as $rowTelefono) {
			$modelTelefono = new Application_Model_Telefono($rowTelefono->toArray());
			//$modelTelefono->setIdTelefono($rowTelefono->idTelefono);
			
			$modelTelefonos[] = $modelTelefono;
		}
		
		return $modelTelefonos;
	}
	
	
	public function crearTelefono(Application_Model_Telefono $telefono){
		$tablaTelefono = $this->tablaTelefono;
		$tablaTelefono->insert($telefono->toArray());
	}
	
	public function editarTelefono($idTelefono, Application_Model_Telefono $telefono){
		$tablaTelefono = $this->tablaTelefono;
		$where = $tablaTelefono->getAdapter()->quoteInto("idTelefono = ?", $idTelefono);
			
		$tablaTelefono->update($telefono->toArray(), $where);
	}
	
	public function eliminarTelefono($idTelefono){
		$tablaTelefono = $this->tablaTelefono;
		$where = $tablaTelefono->getAdapter()->quoteInto("idTelefono = ?", $idTelefono);
		
		$tablaTelefono->delete($where);
	}
	
}

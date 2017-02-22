<?php
/**
 * 
 */
class Sistema_DAO_Usuario implements Sistema_Interfaces_IUsuario {
	
	private $tablaUsuario;
	private $tablaRol;
	
	function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaRol = new Sistema_Model_DbTable_Rol(array('db'=>$dbAdapter));
		$this->tablaUsuario = new Sistema_Model_DbTable_Usuario(array('db'=>$dbAdapter));
	}
	
	public function obtenerUsuario($idUsuario){
		$tablaUsuario = $this->tablaUsuario;
		$select = $tablaUsuario->select()->from($tablaUsuario)->where("idUsuario = ?",$idUsuario);
		$rowUsuario = $tablaUsuario->fetchRow($select);
		
		$modelUsuario = new Sistema_Model_Usuario($rowUsuario->toArray());
		return $modelUsuario;
	}
	public function obtenerUsuarios($idRol){
		$tablaUsuario = $this->tablaUsuario;
		$select = $tablaUsuario->select()->from($tablaUsuario)->where("idRol = ?",$idRol);
		$rowsUsuarios = $tablaUsuario->fetchAll($select);
		$modelUsuarios = array();
		foreach ($rowsUsuarios as $row) {
			$modelUsuario = new Sistema_Model_Usuario($row->toArray());
			$modelUsuarios[] = $modelUsuario;
		}
		
		return $modelUsuarios;
	}
	public function crearUsuario(Sistema_Model_Usuario $usuario){
		$tablaUsuario = $this->tablaUsuario;
		//$usuario->setHash($usuario->getHash());
		$usuario->setFecha(date("Y-m-d H:i:s", time()));
		$tablaUsuario->insert($usuario->toArray());
	}
	public function editarUsuario($idUsuario, array $usuario){
		$tablaUsuario = $this->tablaUsuario;
		$where = $tablaUsuario->getAdapter()->quoteInto("idUsuario = ?", $idUsuario);
		$tablaUsuario->update($usuario, $where);
	}
	public function eliminarUsuario($idUsuario){
		$tablaUsuario = $this->tablaUsuario;
		$where = $tablaUsuario->getAdapter()->quoteInto("idUsuario = ?", $idUsuario);
		$tablaUsuario->delete($where);
	}
}

<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Sistema_DAO_Parametro implements Sistema_Interfaces_IParametro {
	
	private $tablaParametro;
	private $tablaSubparametro;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaParametro = new Sistema_Model_DbTable_Parametro(array('db'=>$dbAdapter));
		$this->tablaSubparametro = new Sistema_Model_DbTable_Subparametro(array('db'=>$dbAdapter));
	}
	
	public function obtenerParametro($idParametro){
		$tablaParametro = $this->tablaParametro;
		$select = $tablaParametro->select()->from($tablaParametro)->where("idParametro = ?", $idParametro);
		$rowParametro = $tablaParametro->fetchRow($select);
		$modelParametro = new Sistema_Model_Parametro($rowParametro->toArray());
		
		return $modelParametro;
	}
	
	public function obtenerParametros(){
		$rowsParametros = $this->tablaParametro->fetchAll();
		$modelParametros = array();
		foreach ($rowsParametros as $row) {
			$modelParametro = new Sistema_Model_Parametro($row->toArray());
			$modelParametros[] = $modelParametro;
		}
		
		return $modelParametros;
	}
	
	public function obtenerSubparametros($idParametro){
		$rowsSubparametros = $this->tablaSubparametro->fetchAll();
		$modelSubparametros = array();
		foreach ($rowsSubparametros as $row) {
			$modelSubparametro = new Sistema_Model_Subparametro($row->toArray());
			$modelSubparametros[] = $modelSubparametro;
		}
		
		return $modelSubparametros;
	}
	
	public function crearParametro(Sistema_Model_Parametro $parametro){
		$parametro->setHash($parametro->getHash());
		$parametro->setFecha(date("Y-m-d H:i:s", time()));
		
		$this->tablaParametro->insert($parametro->toArray());
	}
	
	public function editarParametro($idParametro, array $parametro){
		$tablaParametro = $this->tablaParametro;
		$where = $tablaParametro->getAdapter()->quoteInto("idParametro = ?", $idParametro);
		$tablaParametro->update($parametro, $where);
	}

}
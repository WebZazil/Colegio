<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
 class Inventario_DAO_Domicilio implements Inventario_Interfaces_IDomicilio {
	
	private $tablaEstado;
	private $tablaMunicipio;
	private $tablaDomicilio;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaEstado = new Sistema_Model_DbTable_Estado(array('db'=>$dbAdapter));
		$this->tablaMunicipio = new Sistema_Model_DbTable_Municipio(array('db'=>$dbAdapter));
		$this->tablaDomicilio = new Sistema_Model_DbTable_Domicilio(array('db'=>$dbAdapter));
	}
	
	public function obtenerEstados(){
		$tablaEstado = $this->tablaEstado;
		$rowsEstados = $tablaEstado->fetchAll();
		$modelsEstados = array();
		
		foreach ($rowsEstados as $rows) {
			$modelEstado = new Sistema_Model_Estado($rows->toArray());
			$modelsEstados[] = $modelEstado;
		}
		
		return $modelsEstados;
	}
	
	public function obtenerMunicipios($idEstado){
		$tablaMunicipio = $this->tablaMunicipio;
		$select = $tablaMunicipio->select()->from($tablaMunicipio)->where("idEstado = ?", $idEstado);
		$rowsMunicipio = $tablaMunicipio->fetchAll($select);
		$modelsMunicipios = array();
		foreach ($rowsMunicipio as $row) {
			$modelMunicipio = new Sistema_Model_Municipio($row->toArray());
			$modelsMunicipios[] = $modelMunicipio;
		}
		
		return $modelsMunicipios;
	}
	
	public function obtenerDomicilio($idDomicilio){
		$tablaDomicilio = $this->tablaDomicilio;
		$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("idDomicilio = ?", $idDomicilio);
		$rowDomicilio = $tablaDomicilio->fetchRow($select);
		
		$domicilioModel = new Sistema_Model_Domicilio($rowDomicilio->toArray());
		
		return $domicilioModel;
	}
	
	public function obtenerDomicilios(){
		$tablaDomicilio = $this->tablaDomicilio;
		$rowDomicilios = $tablaDomicilio->fetchAll();
		
		$modelDomicilios = array();
		
		foreach ($rowDomicilios as $rowDomicilio) {
			$modelDomicilio = new Sistema_Model_Domicilio($rowDomicilio->toArray());
			$modelDomicilio->setIdDomicilio($rowDomicilio->idDomicilio);
			
			$modelDomicilios[] = $modelDomicilio;
		}
		
		return $modelDomicilios;
	}
	
	public function crearDomicilio(Sistema_Model_Domicilio $domicilio){
		$tablaDomicilio = $this->tablaDomicilio;
		$domicilio->setHash($domicilio->getHash());
		$tablaDomicilio->insert($domicilio->toArray());
	}
	
	public function editarDomicilio($idDomicilio, array $domicilio){
		$tablaDomicilio = $this->tablaDomicilio;
		$where = $tablaDomicilio->getAdapter()->quoteInto("idDomicilio = ?", $idDomicilio);
		$tablaDomicilio->update($domicilio, $where);
	}
	
	public function eliminarDomicilio($idDomiclio){
		$tablaDomicilio = $this->tablaDomicilio;
		$where = $tablaDomicilio->getAdapter()->quoteInto("idDomicilio = ?", $idDomicilio);
		$tablaDomicilio->delete($where);
	}
 }
<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
 class Sistema_DAO_Domicilio implements Sistema_Interfaces_IDomicilio {
 	
	private $tablaDomicilio;
	private $tablaFiscales;
	private $tablaFiscalesDomicilios;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaDomicilio = new Sistema_Model_DbTable_Domicilio(array('db'=>$dbAdapter));
		$this->tablaFiscales = new Sistema_Model_DbTable_Fiscales(array('db'=>$dbAdapter));
		$this->tablaFiscalesDomicilios = new Sistema_Model_DbTable_FiscalesDomicilios(array('db'=>$dbAdapter));
	}
	
	public function obtenerDomicilio($idDomicilio) {
		$tablaDomicilio = $this->tablaDomicilio;
		$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("idDomicilio = ?", $idDomicilio);
		$rowDomicilio = $tablaDomicilio->fetchRow($select);
		
		$modelDomicilio = new Sistema_Model_Domicilio($rowDomicilio->toArray());
		return $modelDomicilio;
	}
	
	public function obtenerDomicilioFiscal($idFiscal) {
		$tablaDomicilio = $this->tablaDomicilio;
		$tablaFiscales = $this->tablaFiscales;
		$select = $tablaFiscales->select()->from($tablaFiscales)->where("idFiscal = ?", $idFiscal);
		$rowFiscales = $tablaFiscales->fetchRow($select);
		$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("idDomicilio = ?", $rowFiscales->idDomicilio);
		$rowDomicilio = $tablaDomicilio->fetchRow($select);
		$modelDomicilio = new Sistema_Model_Domicilio($rowDomicilio->toArray());
		
		return $modelDomicilio;
	}
	
	public function obtenerDomiciliosFiscales() {
		$tablaDomicilio = $this->tablaDomicilio;
		$tablaFiscales = $this->tablaFiscales;
		$rowsFiscales = $tablaFiscales->fetchAll();
		$modelDomicilios = array();
		
		foreach ($rowsFiscales as $row) {
			$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("idDomicilio = ?", $row->idDomicilio);
			$rowDomicilio = $tablaDomicilio->fetchRow($select);
			$modelDomicilio = new Sistema_Model_Domicilio($rowDomicilio->toArray());
			$modelDomicilios[$row->idFiscales] = $modelDomicilio;
		}
		
		return $modelDomicilios;
	}
	
	public function obtenerDomicilioSucursal($idSucursal){}
	public function obtenerDomiciliosSucursales($idFiscal){}
	public function obtenerDomiciliosSucursalesEstado($idFiscal, $idEstado){}
	
	public function crearDomicilio(Sistema_Model_Domicilio $domicilio){
		$tablaDomicilio = $this->tablaDomicilio;
		$domicilio->setHash($domicilio->getHash());
		$tablaDomicilio->insert($domicilio->toArray());
		$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("hash = ?",$domicilio->getHash());
		$rowDomicilio = $tablaDomicilio->fetchRow($select);
		$modelDomicilio = new Sistema_Model_Domicilio($rowDomicilio->toArray());
		
		return $modelDomicilio;
	}
	
	public function crearDomicilioFiscal($idFiscal, Sistema_Model_Domicilio $domicilio) {
		$tablaDomicilio = $this->tablaDomicilio;
		$tablaFiscales = $this->tablaFiscales;
		//Creamos el domicilio en la tabla domicilio
		//$domicilio->setHash($domicilio->getHash());
		$tablaDomicilio->insert($domicilio->toArray());
		//Creamos la relacion en la tabla fiscalesDomicilios
		$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("hash = ?",$domicilio->getHash());
		$rowDomicilio = $tablaDomicilio->fetchRow($select);
		
		$datos = array();
		$datos["idDomicilio"] = $rowDomicilio->idDomicilio;
		//$datos["idFiscales"] = $idFiscal;
		//$datos["esSucursal"] = "N";
		$where = $tablaFiscales->getAdapter()->quoteInto("idFiscales = ?", $idFiscal);
		$tablaFiscales->update($datos, $where);
		//$tablaFiscalesDomicilio->insert($datos);
	}
	
	public function editarDomicilio($idDomiclio, array $domicilio){
		$where = $this->tablaDomicilio->getAdapter()->quoteInto("idDomicilio=?", $idDomiclio);
		$this->tablaDomicilio->update($domicilio, $where);
	}
	
	public function eliminarDomicilio($idDomiclio){}
 }
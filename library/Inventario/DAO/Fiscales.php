<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Inventario_DAO_Fiscales implements Inventario_Interfaces_IFiscales {
	
	private $tablaFiscales;
	private $tablaDomicilio;
	private $tablaTelefono;
	private $tablaEmail;
	
	private $tablaFiscalesDomicilio;
	private $tablaFiscalesTelefonos;
	private $tablaFiscalesEmails;
	
	function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaFiscales = new Sistema_Model_DbTable_Fiscales(array('db'=>$dbAdapter));
		$this->tablaDomicilio = new Sistema_Model_DbTable_Domicilio(array('db'=>$dbAdapter));
		$this->tablaTelefono = new Sistema_Model_DbTable_Telefono(array('db'=>$dbAdapter));
		$this->tablaEmail = new Sistema_Model_DbTable_Email(array('db'=>$dbAdapter));
		
		$this->tablaFiscalesDomicilio = new Sistema_Model_DbTable_FiscalesDomicilios(array('db'=>$dbAdapter));
		$this->tablaFiscalesTelefonos = new Sistema_Model_DbTable_FiscalesTelefonos(array('db'=>$dbAdapter));
		$this->tablaFiscalesEmails = new Sistema_Model_DbTable_FiscalesEmail(array('db'=>$dbAdapter));
	}
	
	public function obtenerFiscales($idFiscales){
		$tablaFiscales = $this->tablaFiscales;
		$select = $tablaFiscales->select()->from($tablaFiscales)->where("idFiscales = ?", $idFiscales);
		$fiscales = $tablaFiscales->fetchRow($select);
		$fiscalesModel = new Sistema_Model_Fiscales($fiscales->toArray());
		$fiscalesModel->setIdFiscales($fiscales->idFiscales);
		
		return $fiscalesModel;
	}
	
	public function obtenerDomicilios($idFiscales){
		$tablaFiscalesDomicilio = $this->tablaFiscalesDomicilio;
		$select = $tablaFiscalesDomicilio->select()->from($tablaFiscalesDomicilio)->where("idFiscales = ?", $idFiscales);
		$referenciasDom = $tablaFiscalesDomicilio->fetchAll($select);
		//===========================================================
		$tablaDomicilio = $this->tablaDomicilio;
		$domicilios = array();
		foreach ($referenciasDom as $referencia) {
			$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("idDomicilio = ?", $referencia->idDomicilio);
			$rowDomicilio = $tablaDomicilio->fetchRow($select);
			
			$domicilioModel = new Sistema_Model_Domicilio($rowDomicilio->toArray());
			$domicilioModel->setIdDomicilio($rowDomicilio->idDomicilio);
			$domicilioModel->setIdEstado($rowDomicilio->idEstado);
			$domicilioModel->setIdMunicipio($rowDomicilio->idMunicipio);
			$domicilios[] = $domicilioModel;
		}
		
		return $domicilios;
	}
	
	public function obtenerTelefonos($idFiscales){
		$tablaFiscalesTelefono = $this->tablaFiscalesTelefonos;
		$select = $tablaFiscalesTelefono->select()->from($tablaFiscalesTelefono)->where("idFiscales = ?", $idFiscales);
		$referenciasTel = $tablaFiscalesTelefono->fetchAll($select);
		//===========================================================
		$tablaTelefono = $this->tablaTelefono;
		$telefonos = array();
		foreach ($referenciasTel as $referencia) {
			$select = $tablaTelefono->select()->from($tablaTelefono)->where("idTelefono = ?", $referencia->idTelefono);
			$rowTelefono = $tablaTelefono->fetchRow($select);
			
			$telefonoModel = new Sistema_Model_Telefono($rowTelefono->toArray());
			$telefonoModel->setIdTelefono($rowTelefono->idTelefono);
			$telefonos[] = $telefonoModel;
		}
		
		return $telefonos;
	}
	
	public function obtenerEmails($idFiscales){
		$tablaFiscalesEmail = $this->tablaFiscalesEmails;
		$select = $tablaFiscalesEmail->select()->from($tablaFiscalesEmail)->where("idFiscales = ?", $idFiscales);
		$referenciasEmail = $tablaFiscalesEmail->fetchAll($select);
		//===========================================================
		$tablaEmail = $this->tablaEmail;
		$email = array();
		foreach ($referenciasEmail as $referencia) {
			$select = $tablaEmail->select()->from($tablaEmail)->where("idEmail = ?", $referencia->idEmail);
			$rowEmail= $tablaEmail->fetchRow($select);
			
			$emailModel = new Sistema_Model_Email($rowEmail->toArray());
			$emailModel->setIdEmail($rowEmail->idEmail);
			$email[] = $emailModel;
		}
		
		return $email;
		
	}
}

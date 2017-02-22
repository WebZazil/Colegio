<?php
/**
 * 
 */
class Sistema_DAO_Sucursal implements Sistema_Interfaces_ISucursal {
	
	private $tablaSucursal;
	private $tablaDomicilio;
	private $tablaTelefono;
	private $tablaEmail;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaSucursal = new Sistema_Model_DbTable_Sucursal(array('db'=>$dbAdapter));
		$this->tablaDomicilio = new Sistema_Model_DbTable_Domicilio(array('db'=>$dbAdapter));
		$this->tablaTelefono = new Sistema_Model_DbTable_Telefono(array('db'=>$dbAdapter));
		$this->tablaEmail = new Sistema_Model_DbTable_Email(array('db'=>$dbAdapter));
	}
	
	/**
	 * Obtenemos una sucursal de la T.Sucursal
	 */
	public function obtenerSucursal($idSucursal){
		/*$tablaSucursal = $this->tablaSucursal;
		$select = $tablaSucursal->select()->from($tablaSucursal)->where("idSucursal=?",$idSucursal);
		$rowSucursal = $tablaSucursal->fetchRow($select);
		
		if(!is_null($rowSucursal)){
			return $rowSucursal->toArray();
		}else{
			return $rowSucursal;
		}*/
		
		//=====================================Modificacion Areli 02 de Agosto 2016
		/*$tablaSucursal = $this->tablaSucursal;
		$select = $tablaSucursal->select()->from($tablaSucursal)->where("idSucursal=?",$idSucursal);
		
		try{
			$rowSucursal = $tablaSucursal->fetchRow($select);
			if(is_null($rowSucursal)){
				return null;
			}else{
				return $rowSucursal->toArray();
			}
			
		}catch(Exception $ex){
			print_r("Excepcion Lanzada: <strong>" . $ex->getMessage()."</strong>");
		}*/
		$tablaSucursal = $this->tablaSucursal;
		$select = $tablaSucursal->select()->from($tablaSucursal)->where("idSucursal = ?",$idSucursal);
		$rowSucursal = $tablaSucursal->fetchRow($select);
		
		$sucursalModel = new Sistema_Model_Sucursal($rowSucursal->toArray());
		$sucursalModel->setIdSucursal($rowSucursal->idSucursal);
		
		return $sucursalModel;
	}
	
	public function obtenerSucursales($idFiscales){
		$tablaSucursal = $this->tablaSucursal;
		$select = $tablaSucursal->select()->from($tablaSucursal)->where("idFiscales=?",$idFiscales);
		$rowsSucursal = $tablaSucursal->fetchAll($select);
		
		if(!is_null($rowsSucursal)){
			return $rowsSucursal->toArray();
		}else{
			return null;
		}
		print_r("$select");
	}	
	
	public function agregarSucursal($idFiscales, array $datos){
		$tablaSucursal = $this->tablaSucursal;
		//Obt
	}
	
	/**
	 * Obtenemos el Objeto model domicilio.
	 */
	public function obtenerDomicilioSucursal($idSucursal){
		$tablaDomicilio = $this->tablaDomicilio;
		$sucursal = $this->obtenerSucursal($idSucursal);
		$select = $tablaDomicilio->select()->from($tablaDomicilio)->where("idDomicilio=?",$sucursal["idDomicilio"]); 
		$rowDomicilio = $tablaDomicilio->fetchRow($select);
		
		if(!is_null($rowDomicilio)){
			return $rowDomicilio->toArray();
		}else{
			return null;
		}
	}
	
	/**
	 * Obtenemos todos los telefonos de la sucursal.
	 */
	public function obtenerTelefonosSucursal($idSucursal){
		$sucursal = $this->obtenerSucursal($idSucursal);
		$idsTelefonosSucursal = explode(",", $sucursal["idsTelefonos"]);
		//print_r($idsTelefonosSucursal);
		$telefonos = array();
		$tablaTelefono = $this->tablaTelefono;
		//print_r("<br />");
		foreach ($idsTelefonosSucursal as $index => $idTelefono) {
			if($idTelefono != ""){	//El ultimo elemento generado por la funcion explode es un string vacio, este ultimo no se toma en cuenta
				//print_r($idTelefono);
				//print_r("<br />");
				$select = $tablaTelefono->select()->from($tablaTelefono)->where("idTelefono=?",$idTelefono);
				//print_r($select->__toString());
				//print_r("<br />");
				$rowTelefono = $tablaTelefono->fetchRow($select);
				//print_r($rowTelefono->toArray());
				//print_r("<br />");
				$telefonos[] = $rowTelefono->toArray();
			}
		}
		//print_r("<br />");
		//print_r($telefonos);
		return $telefonos;
	}
	
	public function obtenerEmailsSucursal($idSucursal){
		$sucursal = $this->obtenerSucursal($idSucursal);
		$idsEmailsSucursal = explode(",", $sucursal["idsEmails"]);
		//print_r($idsTelefonosSucursal);
		$emails = array();
		$tablaEmail = $this->tablaEmail;
		//print_r("<br />");
		foreach ($idsEmailsSucursal as $index => $idEmail) {
			if($idEmail != ""){	//El ultimo elemento generado por la funcion explode es un string vacio, este ultimo no se toma en cuenta
				//print_r($idTelefono);
				//print_r("<br />");
				$select = $tablaEmail->select()->from($tablaEmail)->where("idEmail=?",$idEmail);
				//print_r($select->__toString());
				//print_r("<br />");
				$rowEmail = $tablaEmail->fetchRow($select);
				//print_r($rowTelefono->toArray());
				//print_r("<br />");
				$emails[] = $rowEmail->toArray();
			}
		}
		//print_r("<br />");
		//print_r($telefonos);
		return $emails;
	}
	
	public function editarDomicilioSucursal($idSucursal, $idDomicilio,array $datos){
		$tablaSucursal = $this->tablaSucursal;
	}
	
	public function editarTelefonoSucursal($idSucursal, $idTelefono,array $datos){
		$tablaSucursal = $this->tablaSucursal;
	}
	
	public function editarEmailSucursal($idSucursal, $idEmail,array $datos){
		$tablaSucursal = $this->tablaSucursal;
	}
}

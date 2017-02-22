<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Inventario_DAO_Empresa implements Inventario_Interfaces_IEmpresa {
	
	private $tablaEmpresa;
	private $tablaEmpresas;
	private $tablaClientes;
	private $tablaProveedores;
	
	private $tablaFiscales;
	private $tablaDomicilio;
	private $tablaTelefono;
	private $tablaEmail;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaEmpresa = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));
	
		$this->tablaEmpresas = new Sistema_Model_DbTable_Empresas(array('db'=>$dbAdapter));
		$this->tablaClientes = new Sistema_Model_DbTable_Clientes(array('db'=>$dbAdapter));
		$this->tablaProveedores = new Sistema_Model_DbTable_Proveedores(array('db'=>$dbAdapter));
		
		$this->tablaFiscales = new Sistema_Model_DbTable_Fiscales(array('db'=>$dbAdapter));
		$this->tablaDomicilio = new Sistema_Model_DbTable_Domicilio(array('db'=>$dbAdapter));
		$this->tablaTelefono = new Sistema_Model_DbTable_Telefono(array('db'=>$dbAdapter));
		$this->tablaEmail = new Sistema_Model_DbTable_Email(array('db'=>$dbAdapter));
	}
	
	public function obtenerEmpresas() {
		$tablaEmpresas = $this->tablaEmpresas;
		$rowsEmpresas = $tablaEmpresas->fetchAll();
		//Obtenemos los ids de empresas
		$idsEmpresas = array();
		foreach ($rowsEmpresas as $rowEmpresa) {
			$idsEmpresas[] = $rowEmpresa->idEmpresa;
		}
		
		$tablaEmpresa = $this->tablaEmpresa;
		$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idEmpresa IN (?)",$idsEmpresas);
		$rowsEmpresa = $tablaEmpresa->fetchAll($select);
		
		$idsFiscales = array();
		foreach ($rowsEmpresa as $rowEmpresa) {
			$idsFiscales[] = $rowEmpresa->idFiscales;
		}
		
		$tablaFiscales = $this->tablaFiscales;
		$select = $tablaFiscales->select()->from($tablaFiscales)->where("idFiscales IN (?)",$idsFiscales);
		$rowsFiscales = $tablaFiscales->fetchAll($select);
		//print_r($rowsFiscales->toArray());
		
		return $rowsFiscales->toArray();
	}
	
	public function obtenerClientes(){
		$tablaClientes=$this->tablaClientes;
		$rowClientes=$tablaClientes->fetchAll();
		
		$cliente=array();
		foreach($rowClientes as $rowCliente){
			$clienteModel = new Sistema_Model_Cliente($rowCliente->toArray());
			$clienteModel->setIdCliente($rowCliente->idCliente);
			
			$cliente[]=$clienteModel;
		}
		return $cliente;
	}
	
	public function obtenerProveedores(){
		$tablaProvedores= $this->tablaProveedores;
		$rowProveedores=$tablaProvedores->fetchAll();
		
		$proveedor = array();
		foreach ($rowProveedores as $rowProveedor){
			$proveedorModel = new Sistema_Model_Proveedor($rowProveedor->toArray());
			$proveedorModel->setIdProveedor($rowProveedor->idProveedor);
			
			$proveedor[] = $proveedorModel;
		}
		return $proveedor;
		
	}
	
	public function obtenerInformacion($idEmpresa)
	{
		$tablaEmpresa = $this->tablaEmpresa;
		$select = $tablaEmpresa->select()->from($tablaEmpresa)->where('idEmpresa = ?', $idEmpresa);
		$rowEmpresa = $tablaEmpresa->fetchRow($select);
		print_r("$rowEmpresa");
		$tablaFiscales = $this->tablaFiscales;
		$select = $tablaFiscales->select()
			->setIntegrityCheck(false)
			->from($tablaFiscales, array('rfc', 'razonSocial'))
			->join('FiscalesDomicilios', 'Fiscales.idFiscales = FiscalesDomicilios.idFiscales', array())
			->distinct()
			->join('Domicilio', "Domicilio.idDomicilio = FiscalesDomicilios.idDomicilio", array('calle', "colonia", "codigoPostal", "numeroInterior", "numeroExterior"))
			->join('FiscalesTelefonos', 'Fiscales.idFiscales = FiscalesTelefonos.idfiscales',array())
			->join('Telefono', "Telefono.idTelefono=FiscalesTelefonos.idTelefono", array('telefono'))
			->join('FiscalesEmail', 'Fiscales.idFiscales = FiscalesEmail.idFiscales',array())
			->join('Email',"Email.idEmail=FiscalesEmail.idEmail", array('email'))
			->join('Municipio', "Municipio.idMunicipio = Domicilio.idMunicipio", array('Municipio'))
			->join('Estado',"Estado.idEstado = Municipio.idEstado",array('Estado'))
			->where("Fiscales.idFiscales = ?", $rowEmpresa->idFiscales);
			//print_r("$select");
			
		return $tablaFiscales->fetchRow($select);			
		//return $select->__toString();
		
	}
	public function obtenerInformacionEmpresasIdFiscales()
	{
		$tablaFiscales = $this->tablaFiscales;
		$select = $tablaFiscales->select()
			->setIntegrityCheck(false)
			->from($tablaFiscales, array('idFiscales','rfc', 'razonSocial'))
			->join('Empresa', 'Empresa.idFiscales = Fiscales.idFiscales', array())
			->join('Empresas','Empresas.idEmpresa = Fiscales.idFiscales', array())
			->order("razonSocial ASC");
		//	return $select->__toString();
		return $tablaFiscales->fetchAll($select);	
	}

	
	
	public function obtenerInformacionEmpresas()
	{
		
		/*$tablaFiscales = $this->tablaFiscales;
		$select = $tablaFiscales->select()
			->setIntegrityCheck(false)
			->from($tablaFiscales, array('idFiscales','rfc', 'razonSocial'))
			->join('FiscalesDomicilios', 'Fiscales.idFiscales = FiscalesDomicilios.idFiscales', array())
			->join('Domicilio', "Domicilio.idDomicilio = FiscalesDomicilios.idDomicilio", array('calle', "colonia", "codigoPostal", "numeroInterior", "numeroExterior"))
			->join('FiscalesTelefonos', 'Fiscales.idFiscales = FiscalesTelefonos.idfiscales',array())
			->join('Telefono', "Telefono.idTelefono=FiscalesTelefonos.idTelefono", array('telefono'))
			->join('FiscalesEmail', 'Fiscales.idFiscales = FiscalesEmail.idFiscales',array())
			->join('Email',"Email.idEmail=FiscalesEmail.idEmail", array('email'))
			->join('Municipio', "Municipio.idMunicipio = Domicilio.idMunicipio", array('Municipio'))
			->join('Estado',"Estado.idEstado = Municipio.idEstado", array('Estado'))
			->where("Fiscales.idFiscales = ?", $rowEmpresa->idFiscales);
			
		//	return $select->__toString();
		return $tablaFiscales->fetchAll($select);*/
		
		$tablaEmpresas = $this->tablaEmpresas;
		$rowsEmpresas = $tablaEmpresas->fetchAll();
		
		$modelEmpresas = array();
		foreach ($rowsEmpresas as $row) {
			$modelEmpresa = new Sistema_Model_Empresa($row->toArray());
			$modelEmpresas[] = $modelEmpresa;
		}
		
		return $modelEmpresas;
	
	}
	
	public function crearEmpresa($tipo, Sistema_Model_Fiscal $fiscal, Sistema_Model_Domicilio $domicilio, Sistema_Model_Telefono $telefono, Sistema_Model_Email $email){
		$tablaEmpresa = $this->tablaEmpresa;
		$tablaFiscales = $this->tablaFiscales;
		$tablaDomicilio = $this->tablaDomicilio;
		$tablaTelefono = $this->tablaTelefono;
		$tablaEmail = $this->tablaEmail;
		
		$tablaEmpresas = $this->tablaEmpresas;
		$tablaClientes = $this->tablaClientes;
		$tablaProveedores = $this->tablaProveedores;
		//   ============================================================ Insertamos Fiscales
		$hashFiscal = $fiscal->getHash();
		$fiscal->setHash($hashFiscal);
		$tablaFiscales->insert($fiscal->toArray());
		
		$select = $tablaFiscales->select()->from($tablaFiscales)->where("hash = ?", $hashFiscal);
		$rowFiscal = $tablaFiscales->fetchRow($select);
		
		
		
		switch ($tipo) {
			case 'EM':
				
				break;
			case 'CL':
				
				break;
			case 'PR':
				
				break;
		}
		
	}
}






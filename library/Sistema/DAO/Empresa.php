<?php
/**
 * 
 */
class Sistema_DAO_Empresa implements Sistema_Interfaces_IEmpresa {
	
	private $tablaEmpresa;
	private $tablaFiscales;
	
	private $tablaEmpresas;
	private $tablaClientes;
	private $tablaProveedores;
	
	private $tablaClientesEmpresa;
	private $tablaProveedoresEmpresa;
	
	private $tablaTipoProveedor;
	
	private $tablaDomicilio;
	private $tablaDomiciliosFiscales;
	private $tablaTelefono;
	private $tablaTelefonosFiscales;
	private $tablaEmail;
	private $tablaEmailFiscales;
	
	private $tablaSucursal;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaEmpresa = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));
		
		$this->tablaEmpresas = new Sistema_Model_DbTable_Empresas(array('db'=>$dbAdapter));
		$this->tablaClientes = new Sistema_Model_DbTable_Clientes(array('db'=>$dbAdapter));
		$this->tablaProveedores = new Sistema_Model_DbTable_Proveedores(array('db'=>$dbAdapter));
		
		$this->tablaClientesEmpresa = new Sistema_Model_DbTable_ClientesEmpresa(array('db'=>$dbAdapter));
		$this->tablaProveedoresEmpresa = new Sistema_Model_DbTable_ProveedoresEmpresa(array('db'=>$dbAdapter));
		
		$this->tablaTipoProveedor = new Sistema_Model_DbTable_TipoProveedor(array('db'=>$dbAdapter));
		$this->tablaSucursal = new Sistema_Model_DbTable_Sucursal(array('db'=>$dbAdapter));
		
		$this->tablaTelefono = new Sistema_Model_DbTable_Telefono(array('db'=>$dbAdapter));
	}
	
	/**
	 * Este metodo crea una empresa, el valor almacenado en $datos[0]["tipo"], 
	 * puede ser
	 */
	public function crearEmpresa(array $datos){
		
		$fecha = date('Y-m-d h:i:s', time());
		
		$bd = Zend_Db_Table_Abstract::getDefaultAdapter();
		
		$bd->beginTransaction();
		try{
			//	Obtenemos los datos fiscales
			$fiscal = $datos[0];
			// Vemos si damos de alta una empresa EM, un cliente CL o un proveedor PR
			$tipo = $fiscal["tipo"];
			// Solo es valido cuando damos de alta una empresa (CL y PR) y un proveedor PR
			if(array_key_exists("tipoProveedor", $fiscal)){
				$tipoProveedor = $fiscal["tipoProveedor"];
				unset($fiscal["tipoProveedor"]);
			}
			$cuenta = "";
			if(array_key_exists("cuenta", $fiscal)){
				$cuenta = $fiscal["cuenta"];
				unset($fiscal["cuenta"]);
			}
			//	En la informacion fiscal tipo y tipoProveedor no van a la base de datos
			unset($fiscal["tipo"]);
			unset($fiscal["tipoProveedor"]);
			//	Agregamos al array de fiscales el campo fecha
			$fiscal['fecha'] = $fecha;
			print_r("<br />");
			print_r("<br />");
			if($tipo == "CL"){
				if($fiscal["rfc"] != "XAXX010101000") {
					$select = $bd->select()->from("Fiscales")->where("rfc=?",$fiscal["rfc"]);
					$rowFiscales = $select->query()->fetchAll();
					//print_r(count($rowFiscales));
					if(count($rowFiscales) > 1) {
						throw new Exception("Error: <strong>".$fiscal["razonSocial"]."</strong> ya esta dado de alta en el sistema, RFC duplicado");
						
					}
				}
			}elseif($tipo == "PR"){
				if($fiscal["rfc"] != "XBXX010101000") {
					$select = $bd->select()->from("Fiscales")->where("rfc=?",$fiscal["rfc"]);
					$rowFiscales = $select->query()->fetchAll();
					//print_r(count($rowFiscales));
					if(count($rowFiscales) > 1) {
						throw new Exception("Error: <strong>".$fiscal["razonSocial"]."</strong> ya esta dado de alta en el sistema, RFC duplicado");
						
					}
				}
			}
			
			//	No genero error por lo que procedemos a insertar en la tabla
			$bd->insert("Fiscales", $fiscal);
			// Obtenemos el id autoincrementable de la tabla Fiscales
			$idFiscales = $bd->lastInsertId("Fiscales","idFiscales");
			// Creamos registro en la tabla Empresa
			$bd->insert("Empresa", array("idFiscales"=>$idFiscales));
			// Obtenemos el Id de T.Empresa para insertar en Empresas, Clientes o Proveedores 
			$idEmpresa = $bd->lastInsertId("Empresa", "idEmpresa");
			//Insertamos en empresa, cliente o proveedor
			switch ($tipo) {
				case 'EM':
					$bd->insert("Empresas", array("idEmpresa"=>$idEmpresa));
					$bd->insert("Clientes", array("idEmpresa"=>$idEmpresa, "cuenta"=>$cuenta));
					$bd->insert("Proveedores", array("idEmpresa"=>$idEmpresa,"idTipoProveedor"=>$tipoProveedor));
					break;	
				case 'CL':
					$bd->insert("Clientes", array("idEmpresa"=>$idEmpresa,"cuenta"=>$cuenta));
					break;
				case 'PR':
					$bd->insert("Proveedores", array("idEmpresa"=>$idEmpresa,"idTipoProveedor"=>$tipoProveedor));
					break;
			}
			//Insertamos en domicilio
			unset($datos[1]["idEstado"]);
			$bd->insert("Domicilio", $datos[1]);
			$idDomicilio = $bd->lastInsertId("Domicilio","idDomicilio");
			$bd->insert("FiscalesDomicilios", array("idFiscales"=>$idFiscales,"idDomicilio"=>$idDomicilio,"fecha" => $fecha,"esSucursal"=>"N"));
			
			//Insertamos en telefono
			$bd->insert("Telefono", $datos[2]);
			$idTelefono = $bd->lastInsertId("Telefono", "idTelefono");
			$bd->insert("FiscalesTelefonos", array("idFiscales"=>$idFiscales,"idTelefono"=>$idTelefono));
			
			//Insertamos en email
			$bd->insert("Email", $datos[3]);
			$idEmail = $bd->lastInsertId("Email","idEmail");
			$bd->insert("FiscalesEmail", array("idFiscales"=>$idFiscales,"idEmail"=>$idEmail));
			
			$bd->commit();
			
		}catch(Exception $ex){
			$bd->rollBack();
			print_r($ex->getMessage());
			throw new Util_Exception_BussinessException("Error: Empresa ya registrada en el sistema");
			
		}
	}
	
	

	/**
	 * 
	 */
	public function obtenerEmpresa($idEmpresa){
		$tablaEmpresa = $this->tablaEmpresa;
		$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idEmpresa=?",$idEmpresa);
		
		try{
			$rowEmpresa = $tablaEmpresa->fetchRow($select);
			if(is_null($rowEmpresa)){
				return null;
			}else{
				return $rowEmpresa->toArray();
			}
			
		}catch(Exception $ex){
			print_r("Excepcion Lanzada: <strong>" . $ex->getMessage()."</strong>");
		}
		
		
	}
	
	/**
	 * Obtiene un registro de la tabla empresa, en base al idFiscal proporcionado
	 * @param $idFiscal
	 * @return array | null
	 */
	public function obtenerEmpresaPorIdFiscales($idFiscales){
		$tablaEmpresa = $this->tablaEmpresa;
		$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idFiscales=?",$idFiscales);
		
		try{
			$rowEmpresa = $tablaEmpresa->fetchRow($select);
			if(is_null($rowEmpresa)){
				return null;
			}else{
				return $rowEmpresa->toArray();
			}
			
		}catch(Exception $ex){
			print_r("Excepcion Lanzada: <strong>" . $ex->getMessage()."</strong>");
		}
		
		
	}
	
	public function obtenerIdFiscalesEmpresas(){
		//Obtenemos todas las empresas
		$tablaEmpresas = $this->tablaEmpresas;
		$rowsEmpresas = $tablaEmpresas->fetchAll();
		
		$tablaEmpresa = $this->tablaEmpresa;
		
		$idFiscales = array();
		
		foreach ($rowsEmpresas as $row) {
			$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idEmpresa = ?", $row->idEmpresa);
			$rowEmpresa = $tablaEmpresa->fetchRow($select);
			
			$idFiscales[] = $rowEmpresa->idFiscales;
		}
		
		return $idFiscales;
	}
	
	public function obtenerEmpresasClientes(){
		$tablaEmpresa = $this->tablaEmpresa;
		$tablaClientes = $this->tablaClientes;
		
		$rowsClientes = $tablaClientes->fetchAll();
		
		$eClientes = array();
		
		foreach ($rowsClientes as $row) {
			$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idEmpresa = ?", $row->idEmpresa);
			$rowEmpresa = $tablaEmpresa->fetchRow($select);
			
			$eClientes[] = $rowEmpresa->toArray();
		}
		
		return $eClientes;
	}
	
	public function obtenerEmpresasProveedores(){
		$tablaEmpresa = $this->tablaEmpresa;
		$tablaProveedores = $this->tablaProveedores;
		
		$rowsProveedores = $tablaProveedores->fetchAll();
		
		$eProveedores = array();
		
		foreach ($rowsProveedores as $row) {
			$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idEmpresa = ?", $row->idEmpresa);
			$rowEmpresa = $tablaEmpresa->fetchRow($select);
			
			$eProveedores[] = $rowEmpresa->toArray();
		}
		
		return $eProveedores;
	}
	
	/**
	 * Obtenemos todos los clientes asociados a la empresa (Consulta a Tabla ClientesEmpresa)
	 */
	public function obtenerClientesEmpresa($idFiscales) {
		
		$tablaEmpresa = $this->tablaEmpresa;
		$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idFiscales=?",$idFiscales);
		$rowEmpresa = $tablaEmpresa->fetchRow($select);
		
		$tablaEmpresas = $this->tablaEmpresas;
		$select = $tablaEmpresas->select()->from($tablaEmpresas)->where("idEmpresa=?",$rowEmpresa->idEmpresa);
		$rowEmpresas = $tablaEmpresas->fetchRow($select);
		
		$tablaClientesEmpresa = $this->tablaClientesEmpresa;
		$select = $tablaClientesEmpresa->select()->from($tablaClientesEmpresa)->where("idEmpresas", $rowEmpresas->idEmpresas);
		$rowsClientes = $tablaClientesEmpresa->fetchAll($select);
		
		$clientesFiscales = array();
		
		if(is_null($rowsClientes)){
			return null;
		}else{
			//return $rowsClientes->toArray();
			$idsClientes = array();
			foreach ($rowsClientes as $rowCliente) {
				$idsClientes[] = $rowCliente->idCliente;
			}
			
			$tablaClientes = $this->tablaClientes;
			$select = $tablaClientes->select()->from($tablaClientes)->where("idCliente IN (?)", array_values($idsClientes));
			print_r($select->__toString());
		}
		
		
		
		
	}
	
	/**
	 * Obtenemos todos los proveedores asociados a la empresa (Consulta a Tabla ProveedoresEmpresa)
	 */
	 public function obtenerProveedoresEmpresa($idFiscales) {
	 	$tablaEmpresa = $this->tablaEmpresa;
		$select = $tablaEmpresa->select()->from($tablaEmpresa)->where("idFiscales=?",$idFiscales);
		$rowEmpresa = $tablaEmpresa->fetchRow($select);
		
		$tablaEmpresas = $this->tablaEmpresas;
		$select = $tablaEmpresas->select()->from($tablaEmpresas)->where("idEmpresa=?",$rowEmpresa->idEmpresa);
		$rowEmpresas = $tablaEmpresas->fetchRow($select);
		
		$tablaProveedoresEmpresa = $this->tablaProveedoresEmpresa;
		$select = $tablaProveedoresEmpresa->select()->from($tablaProveedoresEmpresa)->where("idEmpresas=?", $rowEmpresas->idEmpresas);
		$rowsProveedores = $tablaProveedoresEmpresa->fetchAll($select);
		
		if(is_null($rowsProveedores)) {
			return null;
		}else{
			return $rowsProveedores->toArray();
		}
		
	 }
	
	/**
	 * Obtiene todos los elementos de la Tabla Tipo Proveedor
	 * @return array()
	 */
	public function obtenerTipoProveedor()
	{
		$tablaTipoProveedor = $this->tablaTipoProveedor;
		$rows = $tablaTipoProveedor->fetchAll();
		
		if(is_null($rows)){
			return null;
		}else{
			return $rows->toArray();
		}
		
	}
	
	/**
	 * Agrega una nueva sucursal al domicilio fiscal.
	 */
	public function agregarSucursal($idFiscales, array $datos, $tipoSucursal)
	{
		$bd = Zend_Db_Table_Abstract::getDefaultAdapter();
		$fecha = date('Y-m-d h:i:s', time());	
		
		try{
			$bd->beginTransaction();
			$datosSucursal = $datos[0];
			$datosDomicilio = $datos[1];
			$datosTelefonos = $datos[2];
			$datosEmails = $datos[3];
			
			// ===========================================  Insertar Domicilio
			unset($datosDomicilio["idEstado"]); // Este campo no esta en la tabla domicilio
			$bd->insert("Domicilio",$datosDomicilio);
			$idDomicilio = $bd->lastInsertId("Domicilio","idDomicilio");
			
			// ===========================================  Insertar Telefono
			$bd->insert("Telefono",$datosTelefonos);
			$idTelefono = $bd->lastInsertId("Telefono","idTelefono");
			
			// ===========================================  Insertar Email
			$bd->insert("Email",$datosEmails);
			$idEmail = $bd->lastInsertId("Email","idEmail");
			
			// ===========================================  Insertar Sucursal
			$datosSucursal["idFiscales"] = $idFiscales;
			$datosSucursal["idDomicilio"] = $idDomicilio;
			$datosSucursal["idsTelefonos"] = $idTelefono.",";
			$datosSucursal["idsEmails"] = $idEmail.",";
			
			$bd->insert("Sucursal",$datosSucursal);
			
			$bd->commit();
		}catch(Exception $ex){
			$bd->rollBack();
			throw new Util_Exception_BussinessException($ex->getMessage(), 1);
		}
	}
	
	/**
	 * Sucursales de la empresa
	 */
	public function obtenerSucursales($idFiscales){
		$tablaSucursal = $this->tablaSucursal;
		$select = $tablaSucursal->select()->from($tablaSucursal)->where("idFiscales=?",$idFiscales);
		$rowsSucursales = $tablaSucursal->fetchAll($select);
		
		if(is_null($rowsSucursales)){
			return null;
		}else{
			return $rowsSucursales->toArray();
		}
	}
	
	/**
	 * Comprueba que la empresa con IdFiscales proporcionada 
	 * sea parte de las empresas operables en el sistema.
	 */
	public function esEmpresa($idFiscales){
		$esEmpresa = false;
		
		$tablaEmpresas = $this->tablaEmpresas;
		$empresa = $this->obtenerEmpresaPorIdFiscales($idFiscales);
		
		$select = $tablaEmpresas->select()->from($tablaEmpresas)->where("idEmpresa=?",$empresa["idEmpresa"]);
		$rowEmpresas = $tablaEmpresas->fetchRow($select);
		
		if(!is_null($rowEmpresas)) $esEmpresa = true;
		
		return $esEmpresa;
	}
	
	/**
	 * Comprueba que la empresa con IdFiscales proporcionada
	 * sea parte de los clientes del sistema
	 */
	public function esCliente($idFiscales){
		$esCliente = false;
		$tablaCliente = $this->tablaClientes;
		
		$empresa = $this->obtenerEmpresaPorIdFiscales($idFiscales);
		$select = $tablaCliente->select()->from($tablaCliente)->where("idEmpresa=?",$empresa["idEmpresa"]);
		$rowCliente = $tablaCliente->fetchRow($select);
		
		if(!is_null($rowCliente)) $esCliente = true;
		
		return $esCliente;
	}
	
	/**
	 * Comprueba que la empresa con IdFiscales proporcionada
	 * sea parte de los proveedores del sistema
	 */
	public function esProveedor($idFiscales){
		$esProveedor = false;
		$tablaProveedores = $this->tablaProveedores;
		$empresa = $this->obtenerEmpresaPorIdFiscales($idFiscales);
		$select = $tablaProveedores->select()->from($tablaProveedores)->where("idEmpresa=?",$empresa["idEmpresa"]);
		$rowProveedor = $tablaProveedores->fetchRow($select);
		
		if(!is_null($rowProveedor)) $esProveedor = true;
		
		return $esProveedor;
	}
	
	/**
	 * Agrega un nuevo telefono de contacto de la sucursal proporcionada.
	 */
	public function agregarTelefonoSucursal($idSucursal, Sistema_Model_Telefono $telefono){
		$tablaSucursal = $this->tablaSucursal;
		$select = $tablaSucursal->select()->from($tablaSucursal)->where("idSucursal=?",$idSucursal);
		$rowSucursal = $tablaSucursal->fetchRow($select);
		if(!is_null($rowSucursal)){
			//Obtenemos los id'sTelefonos de la sucursal
			$telefonosSucursal = explode(",", $rowSucursal->telefonos);
			//Agregamos telefono a tablaTelefono y con el idGenerado lo agregamos a la sucursal
			//$tablaTelefono = $this->tablaTelefono;
			print_r($telefonosSucursal);
			print_r("<br />");
			$idTelefono = $this->tablaTelefono->insert($telefono->toArray());
			//$idTelefono = $tablaTelefono->get
			$telefonosSucursal[] = $idTelefono;
			$telefonosSucursal = implode(",", $telefonosSucursal);
			print_r($telefonosSucursal);
			print_r("<br />");
		}
	}
	
	/**
	 * 
	 */
	 
	public function obtieneEmpresas($idEmpresa){
		$tablaEmpresas = $this->tablaEmpresas;
		$select = $tablaEmpresas->select()->from($tablaEmpresas)->where("idEmpresa=?",$idEmpresa);
		$rowEmpresas = $tablaEmpresas->fetchRow($select);
		$consecutivo = $rowEmpresas['consecutivo'];
		//print_r("<br />");
		
		$consecutivo = $consecutivo +1 ;
		//print_r($consecutivo);
		
		
		try{
			//$rowEmpresas = $tablaEmpresas->fetchRow($select);
			if(is_null($rowEmpresas)){
				return null;
			}else{
				return $rowEmpresas->toArray();
			}
			
		}catch(Exception $ex){
			print_r("Excepcion Lanzada: <strong>" . $ex->getMessage()."</strong>");
		}
		
		
	} 
	public function agregarEmailSucursal($idSucursal, Sistema_Model_Email $email){
		
	}
	
	public function editarTelefonoSucursal($idSucursal, array $arrayTelefono){}
	public function editarEmailSucursal($idSucursal, array $arrayEmail){}
	
	public function eliminarTelefonoSucursal($idSucursal, $idTelefono){}
	public function eliminarEmailSucursal($idSucursal, $idEmail){}
	
	
}

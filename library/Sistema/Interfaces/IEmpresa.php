<?php
/**
 * 
 */
interface Sistema_Interfaces_IEmpresa {
	
	public function crearEmpresa(array $datos);
	
	public function obtenerEmpresa($idEmpresa);
	public function obtieneEmpresas($idEmpresa);
	public function obtenerEmpresaPorIdFiscales($idFiscales);
	
	public function obtenerIdFiscalesEmpresas();
	//public function obtenerIdFiscalesClientes();
	//public function obtenerIdFiscalesProveedores();
	public function obtenerEmpresasClientes();
	public function obtenerEmpresasProveedores();
	
	public function obtenerTipoProveedor();
	
	public function esEmpresa($idFiscales);
	public function esCliente($idFiscales);
	public function esProveedor($idFiscales);
	// =============================================================== Operaciones sobre sucursales
	
	public function agregarSucursal($idFiscales, array $datos, $tipoSucursal);
	public function obtenerSucursales($idFiscales);
	
	public function agregarTelefonoSucursal($idSucursal, Sistema_Model_Telefono $telefono);
	public function agregarEmailSucursal($idSucursal, Sistema_Model_Email $email);
	
	public function editarTelefonoSucursal($idSucursal, array $arrayTelefono);
	public function editarEmailSucursal($idSucursal, array $arrayEmail);
	
	public function eliminarTelefonoSucursal($idSucursal, $idTelefono);
	public function eliminarEmailSucursal($idSucursal, $idEmail);
	
}

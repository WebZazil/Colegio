<?php
/**
 * 
 */
interface Sistema_Interfaces_IFiscales {
	
	public function obtenerFiscales($idFiscales);
	public function obtenerFiscalesEmpresas();
	public function obtenerFiscalesClientes();
	public function obtenerFiscalesProveedores();
	
	public function obtenerDomiciliosPorIdFiscal($idFiscales);
	//public function obtenerDomicilioFiscal($idFiscales);
	public function obtenerTelefonosFiscales($idFiscales);
	public function obtenerEmailsFiscales($idFiscales);
	
	public function crearFiscales(Sistema_Model_Fiscales $fiscales);
	
	public function agregarDomicilioFiscal($idFiscales, Sistema_Model_Domicilio $domicilio);
	public function agregarTelefonoFiscal($idFiscales, Sistema_Model_Telefono $telefono);
	public function agregarEmailFiscal($idFiscales, Sistema_Model_Email $email);
	
	//	Restructuracion de funciones
	
	public function getFiscalesById($idFiscales);
	public function getFiscalesByIdEmpresa($idEmpresa); // Id de Tabla Empresa - (No de tabla Empresas)
	
	public function getFiscalesEmpresas();
	public function getFiscalesClientes();
	public function getFiscalesProveedores();
	
	public function getFiscalesClientesByIdFiscalesEmpresa($idFiscales);	// Id de Tabla Fiscales
	public function getFiscalesProveedoresByIdFiscalesEmpresa($idFiscales);	// Id de Tabla Fiscales
	
	public function getEmpresaByIdFiscales($idFiscales);
	public function getClienteByIdFiscales($idFiscales);
	
	public function asociateClienteEmpresa($idEmpresas, $idCliente);
	
	
}

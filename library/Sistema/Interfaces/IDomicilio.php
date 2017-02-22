<?php
/**
 * 
 */
interface Sistema_Interfaces_IDomicilio {
	
	public function obtenerDomicilio($idDomicilio);
	public function obtenerDomicilioFiscal($idFiscal);
	public function obtenerDomiciliosFiscales();
	
	public function obtenerDomicilioSucursal($idSucursal);
	public function obtenerDomiciliosSucursales($idFiscal);
	public function obtenerDomiciliosSucursalesEstado($idFiscal, $idEstado);
	
	public function crearDomicilio(Sistema_Model_Domicilio $domicilio);
	public function crearDomicilioFiscal($idFiscal, Sistema_Model_Domicilio $domicilio);
	
	public function editarDomicilio($idDomiclio, array $domicilio);
	public function eliminarDomicilio($idDomiclio);
}

<?php

interface Inventario_Interfaces_IEmpresa {
	public function crearEmpresa($tipo, Sistema_Model_Fiscal $fiscal, Sistema_Model_Domicilio $domicilio, Sistema_Model_Telefono $telefono, Sistema_Model_Email $email);
	//public function obtenerEmpresas($tipo);
	public function obtenerEmpresas();
	public function obtenerClientes();
	public function obtenerProveedores();
	public function obtenerInformacionEmpresasIdFiscales();
}

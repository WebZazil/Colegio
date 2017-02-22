<?php
   interface Sistema_Interfaces_IVendedores {
   	

	public function crearVendedor(array $datos);
	public function obtenerVendedores();
	public function obtenerVendedor($idVendedor);
	public function generarClaveVendedor(array $claves);
  	//public function crearEmpresa(array $datos);
   
   }

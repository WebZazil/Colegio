<?php

interface Sistema_Interfaces_ITelefono {
	public function obtenerTelefono($idTelefono);
	public function obtenerTelefonos();
	public function crearTelefono(Sistema_Model_Telefono $telefono);
	public function crearTelefonoFiscal($idFiscal, Sistema_Model_Telefono $telefono);
	public function editarTelefono($idTelefono, array $telefono);
	public function eliminarTelefono($idTelefono);
}

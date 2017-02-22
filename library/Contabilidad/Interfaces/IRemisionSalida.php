<?php

interface Contabilidad_Interfaces_IRemisionSalida {
	public function restarProducto(array $encabezado, $producto,$formaPago);
	public function obtenerClientes();
	public function editarBanco($formaPago, $productos);
}
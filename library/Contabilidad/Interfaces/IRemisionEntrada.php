<?php

interface Contabilidad_Interfaces_IRemisionEntrada {

	public function obtenerProveedores();
	public function agregarProducto(array $encabezado, $producto, $formaPago);
	public function guardaPago(array $encabezado, $formaPago, $productos);
	//public function actulizaSaldoBanco(array $formaPago);

}

<?php

interface Contabilidad_Interfaces_INotaEntrada {
	
	
	
	public function agregarProducto(array $encabezado, $producto);
	public function obtenerProveedores();
	public function suma(array $encabezado, $producto);
}

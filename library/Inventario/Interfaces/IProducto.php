<?php

interface Inventario_Interfaces_IProducto {
	public function obtenerProducto($idProducto);
	public function obtenerProductos();
	public function crearProducto(Inventario_Model_Producto $producto);
	public function editarProducto($idProducto,array $producto);
	public function eliminarProducto($idProducto);
}

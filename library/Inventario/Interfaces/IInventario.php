<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Inventario_Interfaces_IInventario{
	public function obtenerInventario();
	public function editarInventario($idInventario, array $inventario);
	public function obtenerIdProductoInventario();
	public function obtenerProductoInventario($idInventario);
	public function editarTodo(array $inventario);
	
}

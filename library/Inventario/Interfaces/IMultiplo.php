<?php

interface Inventario_Interfaces_IMultiplo {
	public function obtenerMultiplo($idMultiplos);
	public function obtenerMultiplos($idProducto);
	public function crearMultiplos(Inventario_Model_Multiplos $idMultiplos);
	public function editarMultiplo($idMultiplo, array $datos);
	public function eliminarMultiplo($idMultiplos);
}
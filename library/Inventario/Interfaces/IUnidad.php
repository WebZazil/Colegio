<?php

interface Inventario_Interfaces_IUnidad{
	public function obtenerUnidad($idUnidad);
	public function obtenerUnidades();
	public function crearUnidad(Inventario_Model_Unidad $unidad);
	public function editarUnidad($idUnidad,array $unidad);
	public function eliminarUnidad($idUnidad);
	//public function obtenerMultiplos($idUnidad);
}
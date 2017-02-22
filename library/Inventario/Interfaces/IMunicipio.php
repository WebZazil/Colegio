<?php

interface Inventario_Interfaces_IMunicipio {
	public function obtenerMunicipio($idMunicipio);
	public function obtenerMunicipios($idEstado);
	public function crearMunicipio(Sistema_Model_Municipio $idMunicipio);
	public function editarMunicipio($idMunicipio, Sistema_Model_Municipio $municipio);
	public function eliminarMunicipio($idMunicipio);
}
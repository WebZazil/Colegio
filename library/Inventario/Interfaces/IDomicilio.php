<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Inventario_Interfaces_IDomicilio{
	public function obtenerEstados();
	public function obtenerMunicipios($idEstado);
	
	public function obtenerDomicilio($idDomicilio);
	public function obtenerDomicilios();
	
	public function crearDomicilio(Sistema_Model_Domicilio $domicilio);
	public function editarDomicilio($idDomiclio, array $domicilio);
	public function eliminarDomicilio($idDomiclio);
}

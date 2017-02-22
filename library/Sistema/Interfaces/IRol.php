<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Sistema_Interfaces_IRol {
	public function obtenerRoles();
	public function obtenerRol($idRol);
	public function crearRol(Sistema_Model_Rol $rol);
}

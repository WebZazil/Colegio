<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Sistema_Interfaces_IUsuario {
	public function obtenerUsuario($idUsuario);
	public function obtenerUsuarios($idRol);
	public function crearUsuario(Sistema_Model_Usuario $usuario);
	public function editarUsuario($idUsuario, array $usuario);
	public function eliminarUsuario($idUsuario);
}

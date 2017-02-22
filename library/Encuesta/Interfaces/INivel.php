<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_INivel{
	// =====================================================================================>>>   Buscar
	public function obtenerNiveles();
	public function obtenerNivel($idNivel);
	public function crearNivel(array $nivel);
	public function editarNivel($idNivel, array $datos);
	public function eliminarNivel($idNivel);
}

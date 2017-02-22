<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IRegistro{
	// =====================================================================================>>>   Buscar
	public function obtenerRegistro($idRegistro);
	public function obtenerRegistroReferencia($referencia);
	public function obtenerRegistros();
	public function obtenerDocentes();
	// =====================================================================================>>>   Insertar
	public function crearRegistro(array $registro);
	// =====================================================================================>>>   Actualizar
	public function editarRegistro($idRegistro, array $registro);
	// =====================================================================================>>>   Eliminar
	public function eliminarRegistro($idRegistro);
	
}

<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IPregunta{
	// =====================================================================================>>>   Buscar
	//public function obtenerPregunta($idPregunta);
	//public function obtenerPreguntasEncuesta($idEncuesta);
	//public function obtenerPreguntasAbiertasEncuesta($idEncuesta);
	// =====================================================================================>>>   Crear
	//public function crearPregunta($idPadre, $tipoPadre, Encuesta_Model_Pregunta $pregunta);
	// =====================================================================================>>>   Editar
	//public function editarPregunta($idPregunta, array $pregunta);
	// =====================================================================================>>>   Eliminar
	//public function eliminarPregunta($idPregunta);
	// **************************************************************************************** IMPLEMENTANDO ESTANDAR DE NOMBRES
	public function getPreguntaById($id);
	public function getPreguntasByIdEncuesta($idEncuesta);
	public function getPreguntasAbiertasByIdEncuesta($idEncuesta);
	public function addPregunta($idEncuesta, $tipoPadre, Encuesta_Models_Pregunta $pregunta);
	public function editPregunta($idPregunta, array $pregunta);
	public function deletePregunta($idEncuesta);
	
	
}

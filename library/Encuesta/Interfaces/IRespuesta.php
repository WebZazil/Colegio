<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IRespuesta{
	// =====================================================================================>>>   Buscar
	/** Obtiene las respuestas de todos los usuarios en la encuesta **/
	public function obtenerRespuestasEncuesta($idEncuesta);
	public function obtenerIdPreguntasEncuesta($idEncuesta);
	public function obtenerIdRegistroEncuesta($idEncuesta);
	/** Obtiene las respuestas de un usuario en la encuesta **/
	public function obtenerRespuestasEncuestaUsuario($idEncuesta, $idRegistro);
	public function obtenerRespuestasPreguntaAsignacion($idEncuesta, $idAsignacion,$idPregunta);
	// =====================================================================================>>>   Insertar
	public function crearRespuesta($idEncuesta, Encuesta_Model_Respuesta $respuesta);
	// =====================================================================================>>>   Actualizar
	public function editarRespuesta($idEncuesta, $idRegistro, array $respuesta);
	// =====================================================================================>>>   Eliminar
	public function eliminarRespuesta($idRespuesta);
	public function eliminarRespuestasGrupo($idEncuesta,$idGrupo,$idRegistro);
}

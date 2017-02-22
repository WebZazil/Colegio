<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IPreferencia {
	// =====================================================================================>>>   Buscar
	public function obtenerPreferenciasPregunta($idEncuesta,$idGrupo);
	//public function obtenerPreferenciaGrupo($idGrupo);
	public function obtenerPreferenciaAsignacion($idAsignacion);
	public function obtenerPreferenciaCategoria($idAsignacion, $idGrupo);
	public function obtenerPreferenciaPregunta($idPregunta,$idAsignacion);
	//public function obtenerTotalCategoria($idEncuesta, $idGrupo, $idConjunto);
	public function obtenerTotalPreferenciaGrupo($idGrupo, $idAsignacion);
	//public function obtenerPreferenciasPregunta($idPregunta);
	// =====================================================================================>>>   Insertar
	public function agregarPreferenciaPregunta($idPregunta,$idOpcion,$idGrupo);
	public function agregarPreferenciaPreguntaAsignacion($idAsignacion,$idPregunta,$idOpcion);
	// =====================================================================================>>>   Actualizar
	
	// =====================================================================================>>>   Eliminar
	
	
}

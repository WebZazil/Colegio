<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IEncuesta{
	// ============================================= Buscar
	
	//public function obtenerEncuesta($idEncuesta);
	//public function obtenerEncuestas();
	//public function crearEncuesta(Encuesta_Model_Encuesta $encuesta);
	//public function editarEncuesta($idEncuesta, array $encuesta);
	//public function eliminarEncuesta($idEncuesta);
	
	// ============================================================= Conjunto de elementos
	
	//public function obtenerEncuestasGrupo($idGrupo);
	public function obtenerSecciones($idEncuesta);
	public function obtenerPreguntas($idEncuesta);
	public function obtenerNumeroEncuestasRealizadas($idEncuesta, $idAsignacion);
	public function obtenerEncuestaRealizadaPorAsignacion($idAsignacion);
	public function obtenerEncuestasRealizadasPorAsignacion($idAsignacion);
	public function obtenerEncuestasVigentesRealizadas();
	public function obtenerGruposEncuesta($idEncuesta);
	public function obtenerObjetoEncuesta($idEncuesta, $idAsignacion);
	// =====================================================================================>>>   Insertar
	public function agregarEncuestaGrupo(array $registro);
	public function agregarEncuestaRealizada(array $registro);
	// =====================================================================================>>>   Normalizar Preferencia
	public function normalizarPreferenciasEncuestaAsignacion($idEncuesta, $idAsignacion);
	// **************************************************************************************** IMPLEMENTANDO ESTANDAR DE NOMBRES
	public function getAllEncuestas();
	public function getEncuestaById($id);
	public function addEncuesta(Encuesta_Models_Encuesta $encuesta);
	public function editEncuesta($id, array $datos);
	
	
}

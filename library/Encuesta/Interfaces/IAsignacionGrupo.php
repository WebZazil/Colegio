<?php
interface Encuesta_Interfaces_IAsignacionGrupo {
	
	public function obtenerDocenteAsignacion($idAsignacion);
	public function obtenerGrupoAsignacion($idAsignacion);
	public function obtenerMateriaAsignacion($idAsignacion);
	
	public function obtenerAsignacionesDocente($idDocente);
	public function obtenerAsignacionesGrupo($idGrupo);
	public function obtenerIdMateriasDocente($idDocente);
	public function obtenerIdGruposDocente($idDocente);
	
	public function obtenerEvaluacionGeneralDocente($idDocente, $idEncuesta);
	// *************************************************
	public function getAsignacionById($id);
	
}

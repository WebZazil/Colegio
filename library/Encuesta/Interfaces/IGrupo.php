<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IGrupo {
	// =====================================================================================>>>   Buscar
	//public function obtenerGrupo($idGrupo);
	//public function obtenerPreguntas($idGrupo);
	public function obtenerValorMayorOpcion($idGrupo);
	public function obtenerValorMenorOpcion($idGrupo);
	// =====================================================================================>>>   Crear
	//public function crearGrupo($idSeccion, Encuesta_Model_Grupo $grupo);
	// =====================================================================================>>>   Editar
	//public function editarGrupo($idGrupo, array $grupo);
	// =====================================================================================>>>   Eliminar
	//public function eliminarGrupo($idGrupo);
	public function eliminarPreguntas($idGrupo);
	// **************************************************************************************** IMPLEMENTANDO ESTANDAR DE NOMBRES
	public function getGruposByIdSeccion($idSeccion);
	public function getGrupoById($id);
	public function addGrupoToSeccion(Encuesta_Models_Grupo $grupo);
	public function editGrupo($id, array $datos);
	
	public function getPreguntasByIdGrupo($idGrupo);
}

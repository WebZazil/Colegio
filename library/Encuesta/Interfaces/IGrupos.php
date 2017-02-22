<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IGrupos {
	
	public function obtenerGrupos($idGrado,$idCiclo);
	public function obtenerGrupo($idGrupo);
	public function obtenerDocentes($idGrupo);
	public function obtenerAsignacion($idAsignacion);
	//public function crearGrupo($idGrado,$idCiclo,array $grupo);
	public function crearGrupo(array $grupo);
	
	public function agregarDocenteGrupo(array $registro);
}

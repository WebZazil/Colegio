<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_IPlan{
	// =====================================================================================>>>   Buscar
	public function obtenerPlanEstudios($idPlan);
	public function obtenerPlanEstudiosVigente();
	public function obtenerPlanesDeEstudio();
	// =====================================================================================>>>   Insertar
	public function agregarPlanEstudios(array $plan);
	// =====================================================================================>>>   Actualizar
	public function actualizarPlanEstudios($idPlan, $datos);
	// =====================================================================================>>>   Eliminar
	public function eliminarPlanEstudios($idPlan);
}

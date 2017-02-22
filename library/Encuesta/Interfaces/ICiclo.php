<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Encuesta_Interfaces_ICiclo{
	// =====================================================================================>>>   Buscar
	//public function obtenerCiclos($idPlan);
	//public function obtenerCiclo($idCiclo);
	//public function obtenerCicloActual();
	//public function crearCiclo(array $ciclo);
	//public function editarCiclo($idCiclo, array $datos);
	//public function eliminarCiclo($idCiclo);
	// ************************************************* Implementando Estandar de Nombres
	public function getCiclosbyIdPlan($idPlan);
	public function getCicloById($id);
	public function getCurrentCiclo();
	public function addCiclo(Encuesta_Models_Ciclo $ciclo);
	public function editCiclo($id, array $datos);
	public function deleteCiclo($id);
	
}

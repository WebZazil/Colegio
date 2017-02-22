<?php
interface Encuesta_Interfaces_IEvaluacion{
	
	public function obtenerCapaEvaluacion($idCapa);
	public function obtenerCapasEvaluacion($idGrupo);
	public function agregarCapaEvaluacion($idGrupo, $nombreCapa);
}

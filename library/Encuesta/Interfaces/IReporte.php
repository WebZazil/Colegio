<?php
interface Encuesta_Interfaces_IReporte {
	public function agregarReporteGrupal($nombreReporte,$idEncuesta,$idAsignacion);
	public function obtenerReporte($idReporte);
}

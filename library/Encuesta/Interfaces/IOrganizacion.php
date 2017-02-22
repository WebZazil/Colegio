<?php
/**
 * 
 */
interface Encuesta_Interfaces_IOrganizacion {
	
	public function addEncuestaToOrganizacion($idOrganizacion, $idEncuesta);
    public function addNivelEducativoToOrganizacion($idOrganizacion, $idNivelEducativo);
    public function addRegistroToOrganizacion($idOrganizacion, $idRegistro);
    public function addPlanEducativoToOrganizacion($idOrganizacion, $idPlanEducativo);
    public function addCategoriasRespuestaToOrganizacion($idOrganizacion, $idCategoriasRespuesta);
    
}

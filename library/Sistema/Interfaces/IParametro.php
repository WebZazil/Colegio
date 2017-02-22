<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Sistema_Interfaces_IParametro {
	public function obtenerParametros();
	public function obtenerParametro($idParametro);
	public function obtenerSubparametros($idParametro);
	
	public function crearParametro(Sistema_Model_Parametro $parametro);
	public function editarParametro($idParametro, array $parametro);
}

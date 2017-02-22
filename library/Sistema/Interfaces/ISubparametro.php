<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Sistema_Interfaces_ISubparametro {
	public function generarClaveProducto(array $claves);
	public function obtenerSubparametros($idParametro);
	public function obtenerSubparametro($idSubparametro);
	public function crearSubparametro(Sistema_Model_Subparametro $subparametro);
	public function editarSubparametro($idSubParametro,array $subParametro);
	
}

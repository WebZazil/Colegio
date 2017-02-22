<?php

interface Contabilidad_Interfaces_IProyecto {
	
	public function crearProyecto(Contabilidad_Model_Proyecto $proyecto);
	public function obtenerProyectos();
	public function obtenerProyecto($idSucursal);

}
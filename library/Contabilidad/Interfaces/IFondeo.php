<?php

interface Contabilidad_Interfaces_IFondeo {
	
	public function obtenerBancosEmpresas();
	public function guardarFondeo(array $datos);
}

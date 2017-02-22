<?php

interface Contabilidad_Interfaces_ITesoreria {
	public function obtenerEmpleadosNomina();
	public function guardaFondeo(array $empresa, $nomina);
	public function guardaNomina(array $empresa, $nomina);
	public function sumaBanco($nomina);
	public function restaBanco($nomina);
}
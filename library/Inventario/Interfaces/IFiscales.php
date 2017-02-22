<?php

interface Inventario_Interfaces_IFiscales {
	public function obtenerFiscales($idFiscales);
	public function obtenerDomicilios($idFiscales);
	public function obtenerTelefonos($idFiscales);
	public function obtenerEmails($idFiscales);
}

<?php

interface Inventario_Interfaces_IEmail {
	public function obtenerEmail($idEmail);
	public function obtenerEmails();
	public function crearEmail(Application_Model_Email $email);
	public function editarEmail($idEmail, Application_Model_Email $email);
	public function eliminarEmail($idEmail);
}

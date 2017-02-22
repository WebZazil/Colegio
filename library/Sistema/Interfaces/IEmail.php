<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
interface Sistema_Interfaces_IEmail {
	public function obtenerEmail($idEmail);
	public function obtenerEmails();
	public function crearEmail(Sistema_Model_Email $email);
	public function crearEmailFiscales($idFiscales, Sistema_Model_Email $email);
	public function editarEmail($idEmail, array $email);
	public function eliminarEmail($idEmail);
}

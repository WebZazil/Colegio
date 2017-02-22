<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Sistema_DAO_Email implements Sistema_Interfaces_IEmail {
	
	private $tablaEmail;
	private $tablaFiscales;
	
	function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaEmail = new Sistema_Model_DbTable_Email(array('db'=>$dbAdapter));
		$this->tablaFiscales = new Sistema_Model_DbTable_Fiscales(array('db'=>$dbAdapter));
	}
	
	public function obtenerEmail($idEmail){
		$tablaEmail = $this->tablaEmail;
		$select = $tablaEmail->select()->from($tablaEmail)->where("idEmail = ?",$idEmail);
		$rowEmail = $tablaEmail->fetchRow($select);
		$modelEmail = new Sistema_Model_Email($rowEmail->toArray());
		
		return $modelEmail;
	}
	
	public function obtenerEmails() {
		$tablaEmail = $this->tablaEmail;
		$rowsEmails = $tablaEmail->fetchAll();
		
		$modelEmails = array();
		foreach ($rowsEmails as $row) {
			$modelEmail = new Sistema_Model_Email($row->toArray());
			$modelEmails[] = $modelEmail;
		}
		
		return $modelEmails;
	}
	
	public function crearEmail(Sistema_Model_Email $email) {
		$tablaEmail = $this->tablaEmail;
		$email->setHash($email->getHash());
		$email->setFecha(date("Y-m-d H:i:s", time()));
		
		$tablaEmail->insert($email->toArray());
		$select = $tablaEmail->select()->from($tablaEmail)->where("hash = ?", $email->getHash());
		$rowEmail = $tablaEmail->fetchRow($select);
		$modelEmail = new Sistema_Model_Email($rowEmail->toArray());
		return $modelEmail;
	}
	
	public function crearEmailFiscales($idFiscales, Sistema_Model_Email $email){
		$tablaEmail = $this->tablaEmail;
		$email->setHash($email->getHash());
		$email->setFecha(date("Y-m-d H:i:s", time()));
		
		$tablaEmail->insert($email->toArray());
		$select = $tablaEmail->select()->from($tablaEmail)->where("hash = ?", $email->getHash());
		$rowEmail = $tablaEmail->fetchRow($select);
		$modelEmail = new Sistema_Model_Email($rowEmail->toArray());
		
		$tablaFiscal = $this->tablaFiscales;
		$select = $tablaFiscal->select()->from($tablaFiscal)->where("idFiscales = ?",$idFiscales);
		$rowFiscal = $tablaFiscal->fetchRow($select);
		
		$idsEmails = explode(",", $rowFiscal->idsEmails);
		if(!array_key_exists($rowEmail->idEmail, $idsEmails)) $rowFiscal->idsEmails .= $rowEmail->idEmail;
		$rowFiscal->save();
		
	}
	
	public function editarEmail($idEmail, array $datos){
		$tablaEmail = $this->tablaEmail;
		$where = $tablaEmail->getAdapter()->quoteInto("idEmail=?", $idEmail);
		$tablaEmail->update($datos, $where);
		
	}
	public function eliminarEmail($idEmail){}
}

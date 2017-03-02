<?php
/**
 * 
 */
class Encuesta_DAO_Evaluacion implements Encuesta_Interfaces_IEvaluacion {
	
	private $tablaConjuntoEvaluador;
	private $tablaEvaluador;
	
	function __construct($dbAdapter) {
		$this->tablaConjuntoEvaluador = new Encuesta_Model_DbTable_ConjuntoEvaluador(array('db'=>$dbAdapter));
		$this->tablaEvaluador = new Encuesta_Model_DbTable_Evaluador(array('db'=>$dbAdapter));
	}
	
	public function getEvaluadoresByTipo($tipo) {
		$tablaEvaluador = $this->tablaEvaluador;
		$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("tipo=?",$tipo);
		$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
		
		if (is_null($rowsEvaluadores)) {
			return null;
		} else {
			return $rowsEvaluadores->toArray();
		}
	}
	
	public function addEvaluador(array $evaluador) {
		$tablaEvaluador = $this->tablaEvaluador;
		
		$evaluador["creacion"] = date("Y-m-d H:i:s");
		$tablaEvaluador->insert($evaluador);
	}
	
	public function getEvaluadorById($idEvaluador) {
		$tablaEvaluador = $this->tablaEvaluador;
		$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador=?",$idEvaluador);
		$rowEvaluador = $tablaEvaluador->fetchRow($select);
		
		if (is_null($rowEvaluador)) {
			return null;
		}else{
			return $rowEvaluador->toArray();
		}
	}
	
	public function getEvaluadoresByNombresOApellidos($nombres, $apellidos) {
		$tablaEvaluadores = $this->tablaEvaluador;
		$db = $tablaEvaluadores->getAdapter();//->quoteInto($text, $value);
		$where = null;
		
		if ((!is_null($nombres) || $nombres != "") && (!is_null($apellidos) || $apellidos != "")) {
			$where = $tablaEvaluadores->select()->from($tablaEvaluadores)->where("nombres LIKE ?", "%{$nombres}%" )->where("nombres LIKE ?", "%{$apellidos}%" );
			//print_r("Q1");
		} elseif ((is_null($nombres) || $nombres == "") && (!is_null($apellidos) || ($apellidos != "")) ) {
			$where = $db->quoteInto("apellidos LIKE ?", "%$apellidos%");
			//print_r("Q2");
		}elseif( (is_null($apellidos) || $apellidos == "") && (!is_null($nombres) || ($nombres != "")) ){
			$where = $db->quoteInto("nombres LIKE ?","%$nombres%");	
			//print_r("Q3");
		}
		
		//print_r("<br />");
		//print_r($where);
		//print_r("<br />");
		$rowsEvaluadores = $tablaEvaluadores->fetchAll($where);
		//print_r($rowsEvaluadores->toArray());
		
		if(is_null($rowsEvaluadores)){
			return null;
		}else{
			return $rowsEvaluadores->toArray();
		}
	}
	
	public function getEvaluadoresByIdConjunto($idConjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaConjunto->fetchRow($where);
		
		if (is_null($rowConjunto)) {
			return array();
		}elseif($rowConjunto->idsEvaluadores != ""){
			$idsEvaluadores = explode(",", $rowConjunto->idsEvaluadores);
		
			$tablaEvaluador = $this->tablaEvaluador;
			$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador IN (?)", $idsEvaluadores);
			$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
			
			return $rowsEvaluadores->toArray();
			
		}else{
			return array();
		}
	}

	/**
	 * Inserta un nuevo conjunto de Evaluacion
	 */
	public function addConjuntoEvaluador($conjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$conjunto["creacion"] = date("Y-m-d H:i:s", time());
		$conjunto["idsEvaluadores"] = "";
		//print_r($conjunto);
		$tablaConjunto->insert($conjunto);
	}
	
	public function getAllConjuntos() {
		$rowsConjuntos = $this->tablaConjuntoEvaluador->fetchAll();
		
		if (is_null($rowsConjuntos)) {
			return array();
		}else{
			return $rowsConjuntos->toArray();
		}
	}
	
	public function getConjuntoById($idConjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaConjunto->fetchRow($where);
		
		if (is_null($rowConjunto)) {
			return null;
		} else {
			return $rowConjunto->toArray();
		}
		
	}
}

<?php
/**
 * 
 */
class Encuesta_DAO_Evaluacion implements Encuesta_Interfaces_IEvaluacion {
	
	private $tablaConjuntoEvaluador;
	private $tablaEvaluador;
	private $tablaEvaluacionesConjunto;
	private $tablaAsignacionGrupo;
	private $tablaEncuesta;	
	
	function __construct($dbAdapter) {
		$this->tablaConjuntoEvaluador = new Encuesta_Model_DbTable_ConjuntoEvaluador(array('db'=>$dbAdapter));
		$this->tablaEvaluador = new Encuesta_Model_DbTable_Evaluador(array('db'=>$dbAdapter));
		$this->tablaEvaluacionesConjunto = new Encuesta_Model_DbTable_EvaluacionesConjunto(array('db'=>$dbAdapter));
		$this->tablaAsignacionGrupo = new Encuesta_Model_DbTable_AsignacionGrupo(array('db'=>$dbAdapter));
		$this->tablaEncuesta = new Encuesta_Model_DbTable_Encuesta(array('db'=>$dbAdapter));
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
	
	public function getConjuntosByIdGrupoEscolar($idGrupoEscolar) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idGrupoEscolar=?", $idGrupoEscolar);
		
		$rowsConjuntos = $tablaConjunto->fetchAll($where);
		
		if(is_null($rowsConjuntos)){
			return array();
		}else{
			return $rowsConjuntos->toArray();
		}
	}
	
	public function getEvaluadoresByString($string) {
		$valor = "'%$string%'";
		$tablaEvaluador = $this->tablaEvaluador;
		$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("nombres LIKE $valor OR apellidos LIKE $valor");
		$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
		
		if(is_null($rowsEvaluadores)){
			return array();
		}else{
			return $rowsEvaluadores->toArray();
		}
	}
	
	public function asociarEvaluadorAConjunto($idEvaluador,$idConjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaConjunto->fetchRow($where);
		
		$idsEvaluadores = explode(",", $rowConjunto->idsEvaluadores);
		
		if(in_array($idEvaluador, $idsEvaluadores)){
			// Esta en el Array, no se agrega nada
		}else{
			$idsEvaluadores[] = $idEvaluador;
		}
		
		$rowConjunto->idsEvaluadores = implode(",", $idsEvaluadores);
		$rowConjunto->save();
	}
	
	public function getAsignacionesByIdConjunto($idConjunto) {
		$tablaEvalsConjunto = $this->tablaEvaluacionesConjunto;
		$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaEvalsConjunto->fetchRow($where);
		
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		
		if(is_null($rowConjunto)){
			return array();
		}else{
			$idsAsignaciones = explode(",", $rowConjunto->idsAsignacionesGrupo); 
			$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo IN (?)", $idsAsignaciones);
			$rowsAsignaciones = $tablaAsignacion->fetchAll($select);
			
			return $rowsAsignaciones->toArray();
		}
	}
	
	public function asociarAsignacionAConjunto($idConjunto, $idAsignacion) {
		$tablaEvalsConjunto = $this->tablaEvaluacionesConjunto;
		$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaEvalsConjunto->fetchRow($where);
		$idsAsignaciones = explode(",", $rowConjunto->idsAsignacionesGrupo);
		
		//print_r($idsAsignaciones);
		
		if (!in_array($idAsignacion, $idsAsignaciones)) {
			$idsAsignaciones[] = $idAsignacion;
			$rowConjunto->idsAsignacionesGrupo = implode(",", $idsAsignaciones);
			$rowConjunto->save();
		}
		
	}
	
	public function asociarEvaluacionAConjunto($idConjunto, $idEncuesta) {
		$tablaEvalsConjunto = $this->tablaEvaluacionesConjunto;
		$datos = array();
		$datos["idConjuntoEvaluador"] = $idConjunto;
		$datos["idsEncuesta"] = implode(",", array($idEncuesta));
		$datos["idsAsignacionesGrupo"] = "";
		
		$tablaEvalsConjunto->insert($datos);
	}
	
	public function getEvaluacionesByIdConjunto($idConjunto) {
		$tablaEvalsConjunto = $this->tablaEvaluacionesConjunto;
		$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaEvalsConjunto->fetchRow($where);
		$idsEncuestas = explode(",", $rowConjunto->idsEncuesta);
		
		$tablaEncuesta = $this->tablaEncuesta;
		$select = $tablaEncuesta->select()->from($tablaEncuesta)->where("idEncuesta IN (?)", $idsEncuestas);
		$rowsEncuestas = $tablaEncuesta->fetchAll($select);
		
		return $rowsEncuestas->toArray(); 
	}
}

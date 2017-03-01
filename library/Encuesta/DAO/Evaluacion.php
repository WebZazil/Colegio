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
	
}

<?php
/**
 * 
 */
class Encuesta_DAO_Evaluacion implements Encuesta_Interfaces_IEvaluacion {
	
	private $tablaECapas;
	
	function __construct() {
		$this->tablaECapas = new Encuesta_Model_DbTable_ECapas;
	}
	
	/**
	 * Obtenemos la capa con el id seleccionado
	 * @var $idCapa
	 */
	public function obtenerCapaEvaluacion($idCapa){
		$tablaECapas = $this->tablaECapas;
		$select = $tablaECapas->select()->from($tablaECapas)->where("idECapas=?",$idCapa);
		$row = $tablaECapas->fetchRow($select);
		
		return $row->toArray();
	}
	
	/**
	 * Obtenemos todas las capas del grupo con id seleccionado
	 * @param $idGrupo
	 * @return array de la tabla ECapas de la BD
	 */
	public function obtenerCapasEvaluacion($idGrupo){
		$tablaECapas = $this->tablaECapas;
		$select = $tablaECapas->select()->from($tablaECapas)->where("idGrupo=?",$idGrupo);
		$rows = $tablaECapas->fetchAll($select);
		
		return $rows->toArray();
	}
	
	public function agregarCapaEvaluacion($idGrupo, $nombreCapa){
		$tablaECapas = $this->tablaECapas;
		$registro = array();
		$registro["idGrupo"] = $idGrupo;
		$registro["nombreCapa"] = $nombreCapa;
		
		$tablaECapas->insert($registro);
	}
}

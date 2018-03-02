<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Nivel implements Encuesta_Interfaces_INivel {
	
	private $tablaNivel;
	
	public function __construct($dbAdapter) {
	    $config = array('db'=>$dbAdapter);
		
		$this->tablaNivel = new Encuesta_Data_DbTable_NivelEducativo($config);
	}
	
	public function obtenerNivel($idNivel){
		$tablaNivel = $this->tablaNivel;
		$select = $tablaNivel->select()->from($tablaNivel)->where("idNivelEducativo = ?",$idNivel);
		$rowNivel = $tablaNivel->fetchRow($select);
		
		return $rowNivel->toArray();
	}
	
	public function obtenerNiveles(){
		$tablaNivel = $this->tablaNivel;
		$rowsNiveles = $tablaNivel->fetchAll();
		
		return $rowsNiveles->toArray();
	}
	
	public function crearNivel(array $nivel){
		$tablaNivel = $this->tablaNivel;
        $tablaNivel->insert($nivel);
	}
	
	public function editarNivel($idNivel, array $datos){
		$tablaNivel = $this->tablaNivel;
		$where = $tablaNivel->getAdapter()->quoteInto("idNivelEducativo = ?", $idNivel);
		$tablaNivel->update($datos, $where);
	}
	
	public function eliminarNivel($idNivel){
		$tablaNivel = $this->tablaNivel;
		$where = $tablaNivel->getAdapter()->quoteInto("idNivelEducativo = ?", $idNivel);
		$tablaNivel->delete($where);
	}
}

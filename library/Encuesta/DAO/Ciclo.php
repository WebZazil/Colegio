<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Ciclo implements Encuesta_Interfaces_ICiclo {
	
	private $tablaCiclo;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaCiclo = new Encuesta_Model_DbTable_CicloEscolar(array('db'=>$dbAdapter));
		//$this->tablaCiclo->setDefaultAdapter($dbAdapter);
	}
    
    /**
     * Obtenemos todos los ciclos escolares sin criterio de búsqueda.
     * 
     */
    public function getAllCiclos() {
        $tablaCiclo = $this->tablaCiclo;
        $rowsCiclos = $tablaCiclo->fetchAll();
        $ciclosEscolares = $rowsCiclos->toArray();
        //print_r($ciclosEscolares);
        return $ciclosEscolares;
    }
	
	public function getCiclosbyIdPlan($idPlan){
		$tablaCiclo = $this->tablaCiclo;
		$select = $tablaCiclo->select()->from($tablaCiclo)->where("idPlanEducativo = ?",$idPlan);
		$rowsCiclos = $tablaCiclo->fetchAll($select);
		$modelCiclos = array();
		
		foreach ($rowsCiclos as $row) { 
			$modelCiclo = new Encuesta_Models_Ciclo($row->toArray());
			$modelCiclos[] = $modelCiclo;
		}
		
		return $modelCiclos;
	}
	
	public function getCicloById($id){
		$tablaCiclo = $this->tablaCiclo;
		$select = $tablaCiclo->select()->from($tablaCiclo)->where("idCicloEscolar = ?",$id);
		$row = $tablaCiclo->fetchRow($select);
		
		$modelCiclo = new Encuesta_Models_Ciclo($row->toArray());
		
		return $modelCiclo;
	}
	/**
	 * Obtiene el ciclo escolar actual
	 */
	public function getCurrentCiclo(){
		$tablaCiclo = $this->tablaCiclo;
		$select = $tablaCiclo->select()->from($tablaCiclo)->where("vigente = ?","1");
		$row = $tablaCiclo->fetchRow($select);
		
		if(is_null($row)){
			throw new Util_Exception_BussinessException("Error: No hay ciclos escolares");
		}
		
		$modelCiclo = new Encuesta_Models_Ciclo($row->toArray());
		
		return $modelCiclo;
	}
	
	/**
	 * Inserta un nuevo ciclo escolar en el modulo de encuestas
	 */
	public function addCiclo(Encuesta_Models_Ciclo $ciclo){
		$tablaCiclo = $this->tablaCiclo;
		$datos = $ciclo->toArray();
		$tablaCiclo->insert($datos);
	}
	
	public function editCiclo($idCiclo, array $datos){
		$tablaCiclo = $this->tablaCiclo;
		$where = $tablaCiclo->getAdapter()->quoteInto("idCicloEscolar = ?", $idCiclo);
		$tablaCiclo->update($datos, $where);
	}
	
	public function deleteCiclo($idCiclo){
		$tablaCiclo = $this->tablaCiclo;
		$where = $tablaCiclo->getAdapter()->quoteInto("idCicloEscolar = ?", $idCiclo);
		$tablaCiclo->delete($where);
	}
}

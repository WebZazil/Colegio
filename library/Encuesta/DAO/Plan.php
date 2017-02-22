<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Plan implements Encuesta_Interfaces_IPlan {
	
	private $tablaPlanEstudios;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaPlanEstudios = new Encuesta_Model_DbTable_PlanEducativo(array('db'=>$dbAdapter));
	}
	
	// =====================================================================================>>>   Buscar
	public function obtenerPlanEstudios($idPlan){
		$tablaPlan = $this->tablaPlanEstudios;
		$select = $tablaPlan->select()->from($tablaPlan)->where("idPlanEducativo=?",$idPlan);
		$plan = $tablaPlan->fetchRow($select);
        //$model = new Encuesta_Models_PlanEducativo();
		return $plan->toArray();
	}
	
	public function obtenerPlanEstudiosVigente(){
		$tablaPlan = $this->tablaPlanEstudios;
		$select = $tablaPlan->select()->from($tablaPlan)->where("vigente=?","1");
		$rowPlan = $tablaPlan->fetchRow($select);
		
		if(is_null($rowPlan)){
			throw new Util_Exception_BussinessException("Error: No hay plan de estudios vigente, seleccione un plan de estudios como vigente");
		}
		
		return $rowPlan->toArray();
	}
	
	public function obtenerPlanesDeEstudio(){
		$tablaPlan = $this->tablaPlanEstudios;
		$planes = $tablaPlan->fetchAll();
		return $planes->toArray();
	}
	// =====================================================================================>>>   Insertar
	/**
	 * Inserta un nuevo plan de estudios en el modulo encuesta
	 */
	public function agregarPlanEstudios(array $plan){
		$tablaPlan = $this->tablaPlanEstudios;
        $tablaPlan->insert($plan);
	}
	// =====================================================================================>>>   Actualizar
	public function actualizarPlanEstudios($idPlan, $datos){
		$tablaPlan = $this->tablaPlanEstudios;
		$where = $tablaPlan->getAdapter()->quoteInto("idPlanEducativo=?", $idPlan);
		$tablaPlan->update($datos, $where);
	}
	// =====================================================================================>>>   Eliminar
	public function eliminarPlanEstudios($idPlan){
		$tablaPlan = $this->tablaPlanEstudios;
		$where = $tablaPlan->getAdapter()->quoteInto("idPlanEducativo=?", $idPlan);
		$tablaPlan->delete($where);
	}
}

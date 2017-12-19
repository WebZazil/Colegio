<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_PlanEducativo {
    
    private $tablePlanEducativo;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePlanEducativo = new Encuesta_Data_DbTable_PlanEducativo($config);
    }
    
    /**
     * 
     * @return array
     */
    public function getAllPlanesEducativos() {
        return $this->tablePlanEducativo->fetchAll()->toArray();
    }
    
    /**
     * 
     * @param int $idPlanEducativo
     * @return array
     */
    public function getPlanEducativoById($idPlanEducativo) {
        $tPE = $this->tablePlanEducativo;
        $select = $tPE->select()->from($tPE)->where('idPlanEducativo=?',$idPlanEducativo);
        $rowPE = $tPE->fetchRow($select);
        
        return $rowPE->toArray();
    }
    
    
    
    
}
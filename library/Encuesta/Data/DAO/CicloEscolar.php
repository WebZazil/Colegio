<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_CicloEscolar {
    
    private $tableCicloEscolar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableCicloEscolar = new Encuesta_Data_DbTable_CicloEscolar($config);
    }
    
    public function getAllCiclosEscolares() {
        return $this->tableCicloEscolar->fetchAll()->toArray();
    }
    
    public function getAllCiclosEscolaresByIdPlanEducativo($idPlanEducativo) {
        $tCE = $this->tableCicloEscolar;
        $select = $tCE->select()->from($tCE)->where('idPlanEducativo=?',$idPlanEducativo);
        $rowsCE = $tCE->fetchAll($select);
        
        return $rowsCE->toArray();
    }
    
    public function getCicloEscolarById($idCicloEscolar) {
        $tCE = $this->tableCicloEscolar;
        $select = $tCE->select()->from($tCE)->where('idCicloEscolar=?',$idCicloEscolar);
        $rowCE = $tCE->fetchRow($select);
        
        return $rowCE->toArray();
    }
    
    public function getCicloEscolarActual() {
        $tCE = $this->tableCicloEscolar;
        $select = $tCE->select()->from($tCE)->where('vigente=?','1');
        $rowCE = $tCE->fetchRow($select);
        
        return $rowCE->toArray();
    }
}
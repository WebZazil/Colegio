<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Evaluador {
    
    private $tableEvaluador;
    private $tableEstatusEvaluador;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableEvaluador = new Encuesta_Data_DbTable_Evaluador($config);
        $this->tableEstatusEvaluador = new Encuesta_Data_DbTable_EstatusEvaluador($config);
    }
    
    /**
     * 
     * @return array
     */
    public function getAllEstatusEvaluadores() {
        $tEE = $this->tableEstatusEvaluador;
        return $tEE->fetchAll()->toArray();
    }
    
    /**
     * 
     * @param string $tipo
     */
    public function getEvaluadoresByTipo($tipo) {
        $tE = $this->tableEvaluador;
        $select = $tE->select()->from($tE)->where('tipo=?',$tipo);
        
        $rowsEvaluadores = $tE->fetchAll($select);
    }
    
    public function addEvaluador($datos) {
        $tE = $this->tableEvaluador;
        return $tE->insert($datos);
    }
    
}
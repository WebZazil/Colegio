<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_OpcionCategoria {
    
    private $tableOpcionCategoria;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableOpcionCategoria = new Encuesta_Data_DbTable_OpcionCategoria($config);
    }
    
    public function getOpcionesByIdCategoria($idCategoria) {
        $tC = $this->tableOpcionCategoria;
        $select = $tC->select()->from($tC)->where('idCategoriasRespuesta=?',$idCategoria);
        $rowC = $tC->fetchRow($select);
        
        return $rowC->toArray();
    }
    
    
}
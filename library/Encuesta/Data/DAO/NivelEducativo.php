<?php
class Encuesta_Data_DAO_NivelEducativo {
    
    private $tableNivelEducativo;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableNivelEducativo = new Encuesta_Data_DbTable_NivelEducativo($config);
        
    }
    
    public function getAllNivelesEducativos() {
        return $this->tableNivelEducativo->fetchAll()->toArray();
    }
    
    public function getNivelEducativoById($idNivelEducativo) {
        $tNE = $this->tableNivelEducativo;
        $select = $tNE->select()->from($tNE)->where('idNivelEducativo=?', $idNivelEducativo);
        $rowNE = $tNE->fetchRow($select);
        
        return $rowNE->toArray(); 
    }
}
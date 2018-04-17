<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Evento_Data_DAO_Asistente {
   
    private $tablaAsistente;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablaAsistente = new Evento_Data_DbTable_Asistente($config);
    }
    
    public function getAsistenteById($idAsistente) {
        $tA = $this->tablaAsistente;
        $select = $tA->select()->from($tA)->where('idAsistente=?',$idAsistente);
        $rowA = $tA->fetchRow($select);
        
        return $rowA->toArray();
    }
    
    public function getAllAsistentes() {
        $tA = $this->tablaAsistente;
        return $tA->fetchAll()->toArray();
    }
}
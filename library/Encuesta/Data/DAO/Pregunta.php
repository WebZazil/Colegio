<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Pregunta {
    
    private $tablePregunta;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePregunta = new Encuesta_Data_DbTable_Pregunta($config);
    }
    
    
    public function getPreguntasByOrigen($origen, $id ) {
        $tP = $this->tablePregunta;
        $select = $tP->select()->from($tP)->where('origen=?',$origen)->where('idOrigen=?',$id);
        $rowsP = $tP->fetchAll($select);
        
        return $rowsP->toArray();
    }
}
<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_GrupoSeccion {
    
    private $tableGrupoSeccion;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableGrupoSeccion = new Encuesta_Data_DbTable_GrupoSeccion($config);
    }
    
    
    
}
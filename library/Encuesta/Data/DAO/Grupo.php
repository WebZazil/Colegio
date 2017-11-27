<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Grupo {
    
    private $tableGrupoEscolar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
    }
    
    /**
     * 
     * @param int $idGrupo
     * @return array
     */
    public function getGrupoById($idGrupo) {
        $tGE = $this->tableGrupoEscolar;
        $select = $tGE->select()->from($tGE)->where('idGrupoEscolar=?',$idGrupo);
        $rowGrupo = $tGE->fetchRow($select);
        
        return $rowGrupo->toArray();
    }
    
    /**
     * 
     * @return array
     */
    public function getAllGrupos() {
        return $this->tableGrupoEscolar->fetchAll()->toArray();
    }
    
}
<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_GrupoEscolar {
    
    private $tableGrupoEscolar;
    private $tableConjuntoEvaluador;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tableConjuntoEvaluador = new Encuesta_Data_DbTable_ConjuntoEvaluador($config);
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
    
    /**
     * 
     * @param int $idGradoEducativo
     * @param int $idCicloEscolar
     * @return array
     */
     public function getGruposByIdGradoEducativoAndIdCicloEscolar($idGradoEducativo, $idCicloEscolar){
        $tGE = $this->tableGrupoEscolar;
        $select = $tGE->select()->from($tGE)->where('idGradoEducativo=?',$idGradoEducativo)->where('idCicloEscolar=?',$idCicloEscolar);
        $rowsGE = $tGE->fetchAll($select);
        
        return $rowsGE->toArray();
    }
    
    /**
     * 
     * @param int $idConjunto
     * @return array
     */
    public function getConjuntoById($idConjunto) {
        $tC = $this->tableConjuntoEvaluador;
        $select = $tC->select()->from($tC)->where('idConjuntoEvaluador=?',$idConjunto);
        $rowCE = $tC->fetchRow($select);
        
        return $rowCE->toArray();
    }
    
    /**
     * 
     * @param int $idGrupoEscolar
     * @return array
     */
    public function getConjuntosByIdGrupoEscolar($idGrupoEscolar) {
        $tC = $this->tableConjuntoEvaluador;
        $select = $tC->select()->from($tC)->where('idGrupoEscolar=?',$idGrupoEscolar);
        $rowsC = $tC->fetchAll($select);
        
        return $rowsC->toArray();
    }
    
    public function addGrupoEscolar($datos) {
        $tGE = $this->tableGrupoEscolar;
        
        return $tGE->insert($datos);
    }
    
    /**
     *
     */
    public function asociarMateriaAgrupo($idGrupoEscolar, $idMateriaEscolar) {
        $tGE = $this->tableGrupoEscolar;
        $select = $tGE->select()->from($tGE)->where("idGrupoEscolar=?",$idGrupoEscolar);
        $rowGrupo = $tGE->fetchRow($select);
        $idsMaterias = '';
        
        if ($rowGrupo->idsMaterias != '') {
            $idsMaterias = explode(",", $rowGrupo->idsMaterias);
        }else{
            $idsMaterias = array();
        }
        
        if (!in_array($idMateriaEscolar, $idsMaterias)) {
            $idsMaterias[] = $idMateriaEscolar;
        }
        
        $rowGrupo->idsMaterias = implode(",", $idsMaterias);
        $rowGrupo->save();
    }
}
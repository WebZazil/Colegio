<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_GradoEducativo {
    
    private $tableGradoEducativo;
    private $tableGrupoEscolar;
    private $tableCicloEscolar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableGradoEducativo = new Encuesta_Data_DbTable_GradoEducativo($config);
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tableCicloEscolar = new Encuesta_Data_DbTable_CicloEscolar($config);
    }
    
    public function getAllGradosEducativosByIdNivelEducativo($idNivelEducativo) {
        $tGE = $this->tableGradoEducativo;
        $select = $tGE->select()->from($tGE)->where('idNivelEducativo=?',$idNivelEducativo);
        $rowsGE = $tGE->fetchAll($select);
        
        return $rowsGE->toArray();
    }
    
    public function getGradoEducativoById($idGradoEducativo) {
        $tGE = $this->tableGradoEducativo;
        $select = $tGE->select()->from($tGE)->where('idGradoEducativo=?',$idGradoEducativo);
        $rowGE = $tGE->fetchRow($select);
        
        return $rowGE->toArray();
    }
    
    public function getGradosEducativosByIdNivelEscolar($idNivel) {
        $tGE = $this->tableGradoEducativo;
        $select = $tGE->select()->from($tGE)->where('idNivelEducativo=?',$idNivel);
        $rowsGrados = $tGE->fetchAll($select);
        
        return $rowsGrados->toArray();
    }
    
    public function getGruposEscolaresByIdGradoEducativo($idGradoEducativo) {
        //Obtenemos ciclo escolar vigente
        $tCE = $this->tableCicloEscolar;
        $select = $tCE->select()->from($tCE)->where('vigente=?','1');
        $rowCicloEscolar = $tCE->fetchRow($select)->toArray();
        
        $tGE = $this->tableGrupoEscolar;
        $select = $tGE->select()->from($tGE)
            ->where('idGradoEducativo=?',$idGradoEducativo)
            ->where('idCicloEscolar=?',$rowCicloEscolar['idCicloEscolar']);
        
        $rowsGE = $tGE->fetchAll($select);
        
        return $rowsGE->toArray();
    }
    
    public function addGradoEducativo($datos) {
        $tGraEd = $this->tableGradoEducativo;
        return $tGraEd->insert($datos);
    }
}
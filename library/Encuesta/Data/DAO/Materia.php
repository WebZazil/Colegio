<?php
class Encuesta_Data_DAO_Materia {
    
    private $tableMateria;
    private $tableAsignacionGrupo;
    private $tableGrupoEscolar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableMateria = new Encuesta_Data_DbTable_MateriaEscolar($config);
        
        $this->tableAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
    }
    
    public function getMateriaById($idMateria) {
        $tM = $this->tableMateria;
        $select = $tM->select()->from($tM)->where('idMateriaEscolar=?',$idMateria);
        $rowMat = $tM->fetchRow($select);
        
        return $rowMat->toArray();
    }
    
    public function getAllMaterias() {
        return $this->tableMateria->fetchAll()->toArray();
    }
    
    public function getMateriasByIdGradoEducativoAndIdCicloEscolar($idGradoEducativo, $idCicloEscolar) {
        $tME = $this->tableMateria;
        $select = $tME->select()->from($tME)
            ->where('idGradoEducativo=?',$idGradoEducativo)
            ->where('idCicloEscolar=?',$idCicloEscolar)
            ->order('materiaEscolar ASC');
        
        $rowsME = $tME->fetchAll($select);
        
        return $rowsME->toArray();
    }
    
    public function getMateriasByIdGrupoEscolar($idGrupoEscolar) {
        $tAG = $this->tableAsignacionGrupo;
        $select = $tAG->select()->from($tAG)->where('idGrupoEscolar=?',$idGrupoEscolar);
        $rowsAG = $tAG->fetchAll($select);
        
        $idsMaterias = array();
        foreach ($rowsAG as $rowAG) {
            $idsMaterias[] = $rowAG['idMateriaEscolar'];
        }
        
        $tM = $this->tableMateria;
        $select = $tM->select()->from($tM)->where('idMateriaEscolar IN (?)', $idsMaterias);
        $rowsM = $tM->fetchAll($select);
        
        return $rowsM->toArray();
    }
    
}
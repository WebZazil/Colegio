<?php
class Encuesta_Data_DAO_Materia {
    
    private $tableMateria;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableMateria = new Encuesta_Data_DbTable_MateriaEscolar($config);
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
    
}
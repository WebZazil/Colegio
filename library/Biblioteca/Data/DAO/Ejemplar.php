<?php
class Biblioteca_Data_DAO_Ejemplar {
    
    private $tableEjemplar;
    private $tableRecurso;
    
    private $tablePais;
    private $tableEditorial;
    private $tableIdioma;
    private $tableTipoLibro;
    private $tableSerieEjemplar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableEjemplar = new Biblioteca_Data_DbTable_Ejemplar($config);
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
        
        $this->tablePais = new Biblioteca_Data_DbTable_Pais($config);
        $this->tableEditorial = new Biblioteca_Data_DbTable_Editorial($config);
        $this->tableIdioma = new Biblioteca_Data_DbTable_Idioma($config);
        $this->tableTipoLibro = new Biblioteca_Data_DbTable_TipoLibro($config);
        $this->tableSerieEjemplar = new Biblioteca_Data_DbTable_SeriesEjemplar($config);
    }
    
    public function getAllRowsTiposLibro() {
        $tTL = $this->tableTipoLibro;
        return $tTL->fetchAll()->toArray();
    }
    
    public function getAllRowsEditoriales() {
        $tEd = $this->tableEditorial;
        return $tEd->fetchAll()->toArray();
    }
    
    public function getAllRowsIdiomas() {
        $tId = $this->tableIdioma;
        return $tId->fetchAll()->toArray();
    }
    
    public function getAllRowsPaises() {
        $tP = $this->tablePais;
        return $tP->fetchAll()->toArray();
    }
    
    public function getAllRowsSeries() {
        $tSE = $this->tableSerieEjemplar;
        return $tSE->fetchAll()->toArray();
    }
    
    public function getRowEjemplar($idEjemplar) {
        $tE = $this->tableEjemplar;
        $select = $tE->select()->from($tE)->where('idEjemplar=?',$idEjemplar);
        $rowEjemplar = $tE->fetchRow($select)->toArray();
        
        return $rowEjemplar;
    }
    
    
    public function getAllRowsEjemplar() {
        $tE = $this->tableEjemplar;
        return  $tE->fetchAll()->toArray();
    }
    
    public function getRowsEjemplaresRecurso($idRecurso) {
        $tE = $this->tableEjemplar;
        $select = $tE->select()->from($tE)->where('idRecurso=?',$idRecurso);
        $rowsEjemplar = $tE->fetchAll($select)->toArray();
        
        return $rowsEjemplar;
    }
    
    
    public function getObjectEjemplar($idEjemplar) {
        $obj = array();
        
        $tE = $this->tableEjemplar;
        $select = $tE->select()->from($tE)->where('idEjemplar=?',$idEjemplar);
        $rowEjemplar = $tE->fetchRow($select)->toArray();
        $obj['ejemplar'] = $rowEjemplar;
        # Table Pais
        $tP = $this->tablePais;
        $select = $tP->select()->from($tP)->where('idPais=?',$rowEjemplar['idPais']);
        $rowPais = $tP->fetchRow($select)->toArray();
        $obj['pais'] = $rowPais;
        # Table Editorial
        $tEd = $this->tableEditorial;
        $select = $tEd->select()->from($tEd)->where('idEditorial=?',$rowEjemplar['idEditorial']);
        $rowEditorial = $tEd->fetchRow($select)->toArray();
        $obj['editorial'] = $rowEditorial;
        # Table Idioma
        $tI = $this->tableIdioma;
        $select = $tI->select()->from($tI)->where('idIdioma=?',$rowEjemplar['idIdioma']);
        $rowIdioma = $tI->fetchRow($select)->toArray();
        $obj['idioma'] = $rowIdioma;
        # Table TipoLibro
        $tTL = $this->tableTipoLibro;
        $select = $tTL->select()->from($tTL)->where('idTipoLibro=?',$rowEjemplar['idTipoLibro']);
        $rowTipoLibro = $tTL->fetchRow($select)->toArray();
        $obj['tipoLibro'] = $rowTipoLibro;
        
        return $obj;
        
    }
    
    public function getAllObjectsEjemplar() {
        $objs = array();
        
        $tE = $this->tableEjemplar;
        
        $ejemplares = $this->getAllRowsEjemplar();
        
        foreach ($ejemplares as $ejemplar) {
            $obj = $this->getObjectEjemplar($ejemplar['idEjemplar']);
            $objs[] = $obj;
        }
        
        return $objs;
    }
    
    
    public function getObjectEjemplaresRecurso($idRecurso) {
        $rowsEjemplar = $this->getRowsEjemplaresRecurso($idRecurso);
        $objs = array();
        
        foreach ($rowsEjemplar as $rowEjemplar) {
            $obj = $this->getObjectEjemplar($rowEjemplar['idEjemplar']);
            
            $objs[] = $obj;
        }
        
        return $objs;
    }
    
    
}
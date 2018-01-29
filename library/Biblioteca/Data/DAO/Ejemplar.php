<?php
class Biblioteca_Data_DAO_Ejemplar {
    
    private $tableEjemplar;
    private $tableRecurso;
    
    private $tablePais;
    private $tableEditorial;
    private $tableIdioma;
    private $tableTipoLibro;
    private $tableSerieEjemplar;
    private $tableInventario;
    private $tableDimensionesEjemplar;
    private $tableEstatusEjemplar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableEjemplar = new Biblioteca_Data_DbTable_Ejemplar($config);
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
        
        $this->tablePais = new Biblioteca_Data_DbTable_Pais($config);
        $this->tableEditorial = new Biblioteca_Data_DbTable_Editorial($config);
        $this->tableIdioma = new Biblioteca_Data_DbTable_Idioma($config);
        $this->tableTipoLibro = new Biblioteca_Data_DbTable_TipoLibro($config);
        $this->tableSerieEjemplar = new Biblioteca_Data_DbTable_SeriesEjemplar($config);
        
        $this->tableInventario = new Biblioteca_Data_DbTable_Inventario($config);
        $this->tableDimensionesEjemplar = new Biblioteca_Data_DbTable_DimensionesEjemplar($config);
        $this->tableEstatusEjemplar = new Biblioteca_Data_DbTable_EstatusEjemplar($config);
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
        # Table SerieEjemplar
        $tSE = $this->tableSerieEjemplar;
        $select = $tSE->select()->from($tSE)->where('idSeriesEjemplar=?',$rowEjemplar['idSeriesEjemplar']);
        $rowSE = $tSE->fetchRow($select)->toArray();
        $obj['serieEjemplar'] = $rowSE;
        # Table Inventario
        $tI = $this->tableInventario;
        $select = $tI->select()->from($tI)->where('idEjemplar=?',$idEjemplar);
        $rowsInventario = $tI->fetchAll($select)->toArray();
        $obj['inventario'] = $rowsInventario;
        # Table DimensionesEjemplar
        $tDE = $this->tableDimensionesEjemplar;
        $select = $tDE->select()->from($tDE)->where('idDimensionesEjemplar=?',$rowEjemplar['idDimensionesEjemplar']);
        $rowDE = $tDE->fetchRow($select)->toArray();
        $obj['dimensiones'] = $rowDE;
        
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
    
    public function altaEjemplar($datos) {
        $tE = $this->tableEjemplar;
        return $tE->insert($datos);
    }
    
    public function altaDimensionesEjemplar($datos) {
        $tDE = $this->tableDimensionesEjemplar;
        return $tDE->insert($datos);
    }
    
    
    public function getAllRowsEstatusEjemplar() {
        $tEE = $this->tableEstatusEjemplar;
        return $tEE->fetchAll()->toArray();
    }
    
    public function getCopiasEjemplar($idEjemplar) {
        $tI = $this->tableInventario;
        $select = $tI->select()->from($tI)->where('idEjemplar=?',$idEjemplar);
        $rowsEjemplar = $tI->fetchAll($select)->toArray();
        
        return $rowsEjemplar;
    }
    
    public function altaCopiaEjemplar($datos) {
        $tI = $this->tableInventario;
        return $tI->insert($datos);
    }
    
    public function getCopiaEjemplarByBarcode($barcode) {
        $tI = $this->tableInventario;
        $select = $tI->select()->from($tI)->where('codigoBarras=?',$barcode);
        $rowCopia = $tI->fetchRow($select);
        
        return $rowCopia->toArray();
    }
    
    public function getCopiaEjemplarByIdCopia($idCopia) {
        $tI = $this->tableInventario;
        $select = $tI->select()->from($tI)->where('idInventario=?',$idCopia);
        $rowCopia = $tI->fetchRow($select);
        
        return $rowCopia->toArray();
    }
}


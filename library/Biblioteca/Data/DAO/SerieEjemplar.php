<?php
class Biblioteca_Data_DAO_SerieEjemplar {
    
    private $tableSerieEjemplar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableSerieEjemplar = new Biblioteca_Data_DbTable_SeriesEjemplar($config);
    }
    
    public function getAllRowsSeriesEjemplar() {
        $tSE = $this->tableSerieEjemplar;
        return $tSE->fetchAll()->toArray();
    }
    
    public function getAllSeries() {
        $tablaSeries = $this->tableSerieEjemplar;
        $select = $tablaSeries->select()->from($tablaSeries)->order("nombreSerie ASC");
        $rowsSeries = $tablaSeries->fetchAll($select);
        
        return $rowsSeries->toArray();
    }
    
    public function getSeriesByParamas(array $params){
        $tablaSerieEjemplar = $this->tableSerieEjemplar;
        $select = $tablaSerieEjemplar->select()->from($tablaSerieEjemplarn);
        
        if(!empty($params)){
            foreach ($params as $key => $value) {
                $select->where($key."=?", $value);
            }
        }
        
        //print_r($select->__toString());
        
        $series = $tablaSerieEjemplar->fetchAll($select);
        return $series->toArray();
        
        
    }
    
    
    public function getSeriesEjemplarById($idSeriesEjemplar) {
        $tSE = $this->tableSerieEjemplar;
        $select = $tSE->select()->from($tSE)->where("idSeriesEjemplar=?",$idSeriesEjemplar);
        $rowSerie = $tSE->fetchRow($select);
        
        //  print_r("$select");
        if (is_null($rowSerie)) {
            return null;
        } else {
            return $rowSerie->toArray();
        }
        
        
    }
    
    
    public function altaEjemplar($datos){
        
        $this->tableSerieEjemplar->insert($data);
        
        
    }
    
    public function editarSerieEjemplar($idSeriesEjemplar, array $datos){
        $tSE = $this->tableSerieEjemplar;
        $where = $tSE->getAdapter()->quoteInto("idSeriesEjemplar=?", $idSeriesEjemplar);
        $tSE->update($data, $where);
        
    }
}
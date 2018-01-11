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
}
<?php


class Encuesta_Data_DAO_Encuesta implements Encuesta_Data_DAO_Interface_IEncuesta {
    
    private $tableEncuesta;
    private $tableSeccionEncuesta;
    
    public function __construct($dbAdapter) {
        $config = array('db'=>$dbAdapter);
        
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableSeccionEncuesta = new Encuesta_Data_DbTable_SeccionEncuesta($config);
    }
    public function getEncuestaByLowerDate($date) {
        
    }

    /**
     * 
     * {@inheritDoc}
     * @see Encuesta_Data_DAO_Interface_IEncuesta::getAllEncuesta()
     */
    public function getAllEncuesta() {
        return $this->tableEncuesta->fetchAll()->toArray();
    }

    /**
     * 
     * {@inheritDoc}
     * @see Encuesta_Data_DAO_Interface_IEncuesta::getEncuestaByStatus()
     */
    public function getEncuestaByStatus($status) {
        $tE = $this->tableEncuesta;
        $select = $tE->select()->from($tE)->where('estatus=?',$status);
        $rowsEncuesta = $tE->fetchAll($select);
        
        return $rowsEncuesta->toArray();
    }

    /**
     * 
     * {@inheritDoc}
     * @see Encuesta_Data_DAO_Interface_IEncuesta::getEncuestaById()
     */
    public function getEncuestaById($idEncuesta) {
        $tE = $this->tableEncuesta;
        $select = $tE->select()->from($tE)->where('idEncuesta=?',$idEncuesta);
        $rowEncuesta = $tE->fetchRow($select);
        
        return $rowEncuesta->toArray();
    }

    public function getEncuestaByUpperDate($date) {
        $tE = $this->tableEncuesta;
        $select = $tE->select()->from($tE)->where('fecha >= (?)', $date);
        $rowsEncuesta = $tE->fetchAll($select);
    }
    
    public function getSeccionesByIdEncuesta($idEncuesta) {
        $tSE = $this->tableSeccionEncuesta;
        $select = $tSE->select()->from($tSE)->where('idEncuesta=?',$idEncuesta);
        $rowsSecciones = $tSE->fetchAll($select);
        
        return $rowsSecciones->toArray();
    }
    
    /**
     * 
     * @param array $data
     * @return integer | array
     */
    public function addEncuesta($data) {
        $tE = $this->tableEncuesta;
        return $tE->insert($data);
    }

}
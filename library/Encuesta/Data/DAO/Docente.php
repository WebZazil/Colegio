<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Docente {
    
    private $tableDocente;
    
    public function __construct($dbAdapter) {
        $config = array('db'=>$dbAdapter);
        
        $this->tableDocente = new Encuesta_Data_DbTable_Docente($config);
    }
    
    /**
     * Regresa todos los docentes del sistema 
     * @return array
     */
    public function getAllDocentes() {
        return $this->tableDocente->fetchAll()->toArray();
    }
    
    /**
     * Regresa el docente especificado
     * @param int $idDocente
     * @return array
     */
    public function getDocenteById($idDocente) {
        $tD = $this->tableDocente;
        $select = $tD->select()->from($tD)->where('idDocente=?',$idDocente);
        $rowDocente = $tD->fetchRow($select);
        
        return $rowDocente->toArray();
    }
    
    /**
     * Agrega un nuevo docente
     * @param array $datos
     * @return mixed|array
     */
    public function addDocente($datos) {
        $tD = $this->tableDocente;
        
        return $tD->insert($datos);
    }
    
    /**
     * 
     * @param int $idDocente
     * @param array $datos
     * @return number
     */
    public function updateDocente($idDocente, $datos) {
        $tD = $this->tableDocente;
        $where = $tD->getAdapter()->quoteInto('idDocente=?', $idDocente);
        
        return $tD->update($datos, $where);
    }
    
    
    public function getDocentesByParams($params) {
        $tD = $this->tableDocente;
        $select = $tD->select()->from($tD);
        
        foreach ($params as $col => $value){
            $select->where($col.' LIKE ? ',"%{$value}%");
        }
        
        $rowsDocentes = $tD->fetchAll($select);
        
        return $rowsDocentes->toArray();
    }
}
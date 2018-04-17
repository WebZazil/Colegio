<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Deportes_Data_DAO_Deporte {
    
    private $tableDeporte;
    private $tableEquipo;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableDeporte = new Deportes_Data_DbTable_Deporte($config);
        $this->tableEquipo = new Deportes_Data_DbTable_Equipo($config);
    }
    
    public function getAllDeportes() {
        $tD = $this->tableDeporte;
        return $tD->fetchAll()->toArray();
    }
    
    public function addDeporte($datos) {
        $tD = $this->tableDeporte;
        return $tD->insert($datos);
    }
    
    public function updateDeporte($idDeporte, $datos) {
        $tD = $this->tableDeporte;
        $where = $tD->getAdapter()->quoteInto('idDeporte=?', $idDeporte);
        
        $tD->update($datos, $where);
    }
    
    public function getDeporteById($idDeporte) {
        $tD = $this->tableDeporte;
        $select = $tD->select()->from($tD)->where('idDeporte=?',$idDeporte);
        $rowD = $tD->fetchRow($select);
        
        return $rowD->toArray();
    }
    
    public function getEquiposByIdDeporte($idDeporte) {
        $tE = $this->tableEquipo;
        $select = $tE->select()->from($tE)->where('idDeporte=?',$idDeporte);
        $rowsEquipos = $tE->fetchAll($select);
        
        return $rowsEquipos->toArray();
    }
    
    
}
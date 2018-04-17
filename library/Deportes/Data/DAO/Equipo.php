<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Deportes_Data_DAO_Equipo {
    
    private $tableEquipo;
    private $tableEstatusEquipo;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableEquipo = new Deportes_Data_DbTable_Equipo($config);
        $this->tableEstatusEquipo = new Deportes_Data_DbTable_EstatusEquipo($config);
    }
    
    public function getAllEstatus() {
        $tEE = $this->tableEstatusEquipo;
        return $tEE->fetchAll()->toArray();
    }
    
    public function getEstatusById($idEstatus) {
        $tEE = $this->tableEstatusEquipo;
        $select = $tEE->select()->from($tEE)->where('idEstatusEquipo=?',$idEstatus);
        $rowEE = $tEE->fetchRow($select);
        
        return $rowEE->toArray();
    }
    
    public function getEquiposByEstatus($estatus) {
        $tEE = $this->tableEstatusEquipo;
        
    }
    
    public function getEquiposByIdDeporte($idDeporte) {
        $tE = $this->tableEquipo;
        $select = $tE->select()->from($tE)->where('idDeporte=?',$idDeporte);
        $rowsE = $tE->fetchAll($select);
        
        return $rowsE->toArray();
    }
    
    public function getObjectsEquipos($rowsEquipos) {
        $container = array();
        $rowsEstatus = $this->getAllEstatus();
        
        foreach ($rowsEquipos as $rowEquipo){
            $obj = array();
            $obj['equipo'] = $rowEquipo;
            
            foreach ($rowsEstatus as $rowEstatus){
                if ($rowEquipo['idEstatusEquipo'] == $rowEstatus['idEstatusEquipo']) {
                    $obj['estatus'] = $rowEstatus;
                    break;
                }
            }
            $container[] = $obj;
        }
        return $container;
    }
    
    public function addEstatusEquipo($datos) {
        $tEE = $this->tableEstatusEquipo;
        return $tEE->insert($datos);
    }
    
    public function editEstatusEquipo($idEstatus,$datos) {
        $tEE = $this->tableEstatusEquipo;
        $where = $tEE->getAdapter()->quoteInto('idEstatusEquipo=?', $idEstatus);
        $tEE->update($datos, $where);
    }
    
    
    public function addEquipo($datos) {
        $tE = $this->tableEquipo;
        return $tE->insert($datos);
    }
}
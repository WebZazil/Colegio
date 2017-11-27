<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class App_Data_DAO_System implements App_Data_DAO_Interface_ISystem {
    
    private $tableModule;
    private $tableModuloOrganizacion;
    private $tableSubscripcion;
    
    public function __construct() {
        $config = array('db' => Zend_Registry::get('zbase'));
        
        $this->tableModule = new App_Data_DbTable_Modulo($config);
        $this->tableModuloOrganizacion = new App_Data_DbTable_ModuloOrganizacion($config);
        $this->tableSubscripcion = new App_Data_DbTable_Subscripcion($config);
    }
    
    public function getModule($idModule) {
        $tM = $this->tableModule;
        $select = $tM->select()->from($tM)->where('idModulo=?',$idModule);
        $rowModule = $tM->fetchRow($select);
        
        return $rowModule->toArray();
    }

    public function editModule($data, $config) {
        
    }

    public function getModules() {
        return $this->tableModule->fetchAll();
    }
    
    public function addModule($data, $config) {
        $tM = $this->tableModule;
        
        
    }
    
    public function getModulesByIdOrganizacion($idOrganizacion) {
        $tM = $this->tableModule;
        $rowsModulos = $tM->fetchAll()->toArray();
        
        $tMO = $this->tableModuloOrganizacion;
        $select = $tMO->select()->from($tMO)->where("idOrganizacion=?",$idOrganizacion);
        $rowsMO = $tMO->fetchAll($select)->toArray();
        
        $container = array();
        
        foreach ($rowsMO as $rowMO) {
            $mod = array();
            $mod['mod'] = $rowMO;
            foreach ($rowsModulos as $rowModulo) {
                if ($rowModulo['idModulo'] == $rowMO['idModulo']) {
                    $mod['obj'] = $rowModulo;
                }
            }
            
            $container[] = $mod;
        }
        
        return $container;
    }
    
    public function getSubscripcionesOrganizacion($idOrganizacion) {
        $tSub = $this->tableSubscripcion;
        $select = $tSub->select()->from($tSub)->where('idOrganizacion=?',$idOrganizacion);
        $rowsSubs = $tSub->fetchAll($select);
        
        return $rowsSubs->toArray();
    }
    
    public function getSubscripcionById($idSubscripcion) {
        $tSub = $this->tableSubscripcion;
        $select = $tSub->select()->from($tSub)->where("idSubscripcion=?",$idSubscripcion);
        $rowSub = $tSub->fetchRow($select);
        
        return $rowSub->toArray();
    }
    
    public function editSubscription($datos, $idSubscripcion) {
        $tSub = $this->tableSubscripcion;
        $where = $tSub->getAdapter()->quoteInto('idSubscripcion=?', $idSubscripcion);
        $tSub->update($datos, $where);
    }
    
}
<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class App_Data_DAO_Usuario implements App_Data_DAO_Interface_IUsuario {
    
    private $tableUsuario;
    private $tableRol;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableUsuario = new App_Data_DbTable_Usuario($config);
        $this->tableRol = new App_Data_DbTable_Rol($config);
    }
    
    public function editUsuarioOrganizacion($idOrganizacion, $container){
        $tUs = $this->tableUsuario;
        $where = $tUs->getDefaultAdapter()->quoteInto("idOrganizacion=?", $idOrganizacion);
        $tUs->update($container, $where);
        
    }

    public function getUsuariosOrganizacion($idOrganizacion){
        $tUs = $this->tableUsuario;
        $tRol = $this->tableRol;
        $select = $tUs->select()->from($tUs)->where('idOrganizacion=?',$idOrganizacion);
        $rowsUsrs = $tUs->fetchAll($select)->toArray();
        $container = array();
        foreach ($rowsUsrs as $rowUs) {
            $select = $tRol->select()->from($tRol)->where('idRol=?',$rowUs['idRol']);
            $rowRol = $tRol->fetchRow($select);
            
            $rowUs['rol'] = $rowRol->toArray();
            $container[] = $rowUs;
        }
        
        return $container;
    }

    public function addUsuarioOrganizacion($container) {
        $tUs = $this->tableUsuario;
        
        return $tUs->insert($container);
    }

    public function deleteUsuarioOrganizacion($container) {
        // NO hace nada aun
    }
    
    public function getUsuarioById($idUsuario) {
        $tUs = $this->tableUsuario;
        $select = $tUs->select()->from($tUs)->where('idUsuario=?',$idUsuario);
        $rowUsuario = $tUs->fetchRow($select);
        
        return $rowUsuario->toArray();
    }
    
    public function getRolUsuario($idUsuario) {
        $tRol = $this->tableRol;
        $usuario = $this->getUsuarioById($idUsuario);
        
        $select = $tRol->select()->from($tRol)->where('idRol=?',$usuario['idRol']);
        $rowRol = $tRol->fetchRow($select);
        
        return $rowRol->toArray();
    }

    public function getRoles() {
        $tRol = $this->tableRol;
        
        return $tRol->fetchAll()->toArray();
    }
    
    public function getRolById($id) {
        $tRol = $this->tableRol;
        $select = $tRol->select()->from($tRol)->where('idRol=?',$id);
        $rowRol = $tRol->fetchRow($select);
        
        return $rowRol->toArray();
    }
    
    public function editRol($datos, $idRol) {
        $tRol = $this->tableRol;
        $where = $tRol->getDefaultAdapter()->quoteInto("idRol=?", $idRol);
        
        $tRol->update($datos, $where);
    }

}
<?php
class Soporte_Data_DAO_Organizacion implements Soporte_Data_DAO_Interfaces_IOrganizacion {
    
    private $tableOrganizacion;
    private $tableSubscripcion;
    private $tableRol;
    private $tableUsuario;
    
    function __construct($dbAdapter, $idOrganizacion) {
        $zbase = Zend_Registry::get('zbase');
        
        $config = array('db' => $zbase);
        
        $this->tableOrganizacion = new Soporte_Data_DbTable_Main_Organizacion($config);
        $this->tableSubscripcion = new Soporte_Data_DbTable_Main_Subscripcion($config);
        $this->tableUsuario = new Soporte_Data_DbTable_Main_Usuario($config);
        $this->tableRol = new Soporte_Data_DbTable_Main_Rol($config);
    }
    
    
    public function getSubscripcionesOrganizacion($idModulo, $idOrganizacion, $tipo = 'Q'){
        $tSub = $this->tableSubscripcion;
        $select = $tSub->select()->from($tSub)
            ->where("idModulo=?",MOD_SOPORTE)
            ->where("idOrganizacion=?",$idOrganizacion)
            ->where("tipo=?",$tipo);
        
        $rowsSubs = $tSub->fetchAll($select);
        return $rowsSubs->toArray();
    }

    public function getUsuariosOrganizacion($idOrganizacion){
        $tUs = $this->tableUsuario;
        $select = $tUs->select()->from($tUs)->where("idOrganizacion=?",$idOrganizacion);
        
        $rowsUsrs = $tUs->fetchAll($select);
        return $rowsUsrs->toArray();
    }

    public function getRolUsuario($idUsuario){
        $tUs = $this->tableUsuario;
        $select = $tUs->select()->from($tUs)->where("idUsuario=?",$idUsuario);
        $rowUs = $tUs->fetchRow($select);
        
        $tRol = $this->tableRol;
        $select = $tRol->select()->from($tRol)->where("idRol=?",$rowUs->idRol);
        $rowRol = $tRol->fetchRow($select);
        
        return $rowRol->toArray();
    }

}
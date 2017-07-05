<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Clasificacion {
	
    private $tableClasificacion;
    
	function __construct() {
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->tableClasificacion = new Biblioteca_Data_DbTable_Clasificacion(array("db"=>$dbAdapter));
	}
    
    public function getAllClasificaciones() {
        $tablaClasif = $this->tableClasificacion;
        $rowsClasificaciones = $tablaClasif->fetchAll();
        
        if (is_null($rowsClasificaciones)) {
            return null;
        } else {
            return $rowsClasificaciones->toArray();
        }
        
    }
    
    public function getClasificacionById($idClasificacion) {
        $tablaClasif = $this->tableClasificacion;
        $select = $tablaClasif->select()->from($tablaClasif)->where("idClasificacion=?",$idClasificacion);
        $rowClasificacion = $tablaClasif->fetchRow($select);
        
        if (is_null($rowClasificacion)) {
            return null;
        } else {
            return $rowClasificacion->toArray();
        }
        
        
    }
    
	
	public function addClasificacion($data)
	{
		$this->tableClasificacion->insert($data);
	}
    
    
}

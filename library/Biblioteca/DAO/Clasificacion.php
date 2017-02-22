<?php
/**
 * 
 */
class Biblioteca_DAO_Clasificacion {
	
    private $tableClasificacion;
    
	function __construct($dbAdapter) {
		$this->tableClasificacion = new Biblioteca_Model_DbTable_Clasificacion(array("db"=>$dbAdapter));
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
    
    
    
}

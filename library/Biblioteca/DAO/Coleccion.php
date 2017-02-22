<?php
/**
 * 
 */
class Biblioteca_DAO_Coleccion {
	
    private $tableColeccion;
    
	function __construct($dbAdapter) {
		
        $this->tableColeccion = new Biblioteca_Model_DbTable_Coleccion(array("db"=>$dbAdapter));
	}
    
    public function getAllColecciones() {
        $tablaColeccion = $this->tableColeccion;
        $rowsColecciones = $tablaColeccion->fetchAll();
        
        if (is_null($rowsColecciones)) {
            return null;
        } else {
            return $rowsColecciones->toArray();
        }
    }
    
    public function getColeccionById($idColeccion) {
        $tablaColeccion = $this->tableColeccion;
        $select = $tablaColeccion->select()->from($tablaColeccion)->where("idColeccion=?",$idColeccion);
        $rowColeccion = $tablaColeccion->fetchRow($select);
        
        if (is_null($rowColeccion)) {
            return null;
        } else {
            return $rowColeccion->toArray();
        }
    
    }
}

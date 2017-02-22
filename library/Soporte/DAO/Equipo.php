<?php
/**
 * 
 */
class Soporte_DAO_Equipo {
	
    private $tableEquipo2;
    
	function __construct($dbAdapter) {
		$this->tableEquipo2 = new Soporte_Model_DbTable_Equipo(array("db"=>$dbAdapter));
	}
    
    public function getAllEquitpos() {
        $tablaEquipo = $this->tableEquipo2;
        $rowsEquipos = $tablaEquipo->fetchAll();
        
        if (is_null($rowsEquipos)) {
            return null;
        } else {
            return $rowsEquipos->toArray();
        }
    }
    
    /**
     * Obtenemos lista de ubicaciones de los equipos
     */
    public function getUbicaciones() {
        $tablaEquipo = $this->tableEquipo2;
        $select = $tablaEquipo->select()->distinct()->from($tablaEquipo,'ubicacion');
        $rowsUbicaciones = $tablaEquipo->fetchAll($select);
        
        if(is_null($rowsUbicaciones)){
            return null;
        }else{
            return $rowsUbicaciones->toArray();
        }
    }
    
    /**
     * 
     */
    public function getUsuarios() {
       $tablaEquipo = $this->tableEquipo2;
       $select = $tablaEquipo->select()->distinct()->from($tablaEquipo,'Usuario');
       $rowsUsuarios = $tablaEquipo->fetchAll($select);
       
       if (is_null($rowsUsuarios)) {
           return null;
       } else {
           return $rowsUsuarios->toArray();
       }
       
         
    }
}

<?php
/**
 * 
 */
class Biblioteca_DAO_Tema {
    
    private $tableTema;
	
	function __construct($dbAdapter) {
		$this->tableTema = new Biblioteca_Model_DbTable_Tema(array("db"=>$dbAdapter));
	}
    
    public function getTemaById($idTema) {
        $tablaTema = $this->tableTema;
        $select = $tablaTema->select()->from($tablaTema)->where("idTema=?",$idTema);
        $rowTema = $tablaTema->fetchRow($select);
        
        if (is_null($rowTema)) {
            return null;
        } else {
            return $rowTema->toArray();
        }
        
    }
    
    public function getAllTemas() {
        $tablaTema = $this->tableTema;
        $rowsTemas = $tablaTema->fetchAll();
        
        if (is_null($rowsTemas)) {
            return null;
        } else {
            return $rowsTemas->toArray();
        }
        
    }
}

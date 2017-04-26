<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Autor {
    
    private $tableAutor;
	
	function __construct($dbAdapter) {
		$this->tableAutor = new Biblioteca_Data_DbTable_Autor(array("db" => $dbAdapter));
	}
    
    public function getAllAutores() {
        $tablaAutor = $this->tableAutor;
        $rowsAutor = $tablaAutor->fetchAll();
        return $rowsAutor->toArray();
    }
    
    public function getAutorById($idAutor) {
        $tablaAutor = $this->tableAutor;
        $select = $tablaAutor->select()->from($tablaAutor)->where("idAutor=?",$idAutor);
        $rowAutor = $tablaAutor->fetchRow($select);
        
        return $rowAutor->toArray();
    }
    
    public function addAutor($data) {
        $this->tableAutor->insert($data);
    }
    
    public function editAutor($idAutor, $data) {
        $tablaAutor = $this->tableAutor;
        $select = $tablaAutor->select()->from($tablaAutor)->where("idAutor=?",$idAutor);
        $rowAutor = $tablaAutor->fetchRow($select);
    }
    
}

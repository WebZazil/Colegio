<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Autor {
    
    private $tableAutor;
	
	function __construct() {
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableAutor = new Biblioteca_Data_DbTable_Autor(array("db" => $dbAdapter));
	}
    
    public function getAllAutores() {
        $tablaAutor = $this->tableAutor;
        //$select = $tablaAutor->select()->from($tablaAutor)->order("nombres ASC");
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
	
	public function getAutoresIndividuales() {
		$tablaAutor = $this->tableAutor;
		$select = $tablaAutor->select()->from($tablaAutor)->where("tipo=?", 'UN');
		$rowsAutores = $tablaAutor->fetchAll($select);
		
		return $rowsAutores->toArray();
	}
	
	public function getAutoresVarios() {
		$tablaAutor = $this->tableAutor;
		$select = $tablaAutor->select()->from($tablaAutor)->where("tipo=?", 'VR');
		$rowsAutores = $tablaAutor->fetchAll($select);
		
		return $rowsAutores->toArray();
	}
	
	
	public function  getAutoresByParams(array $params){
	    $tablaAutor = $this->tableAutor;
	    $select = $tablaAutor->select()->from($tablaAutor);
	    
	    if(!empty($params)){
	        foreach ($params as $key => $value){
	            $select->where($key."?", value);
	        }
	    }
	    
	    $autores = $tablaAutor->fetchAll($select);
	    return $autores->toArray();
	}
	
	public function editarAutor($idAutor, array $datos){
	    $tA = $this->tableAutor;
	    $where = $tA->getAdapter()->quoteInto("idAutor=?", $idAutor);
	    $tA->update($datos, $where);
	}
	
    
}

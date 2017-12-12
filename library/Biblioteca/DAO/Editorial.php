<?php
/*
 * 
 */
 
 class Biblioteca_DAO_Editorial{
 	
	private $tableEditorial;
	
	function __construct(){
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->tableEditorial = new Biblioteca_Model_DbTable_Editorial(array("db"=>$dbAdapter));
		
	}
	
	public function agregarEditorial($data)
	{
		$this->tableEditorial->insert($data);
	}
	

	public function getAllEditoriales()
	{
		$tablaEditorial = $this->tableEditorial;
		$select = $tablaEditorial->select()->from($tablaEditorial)->order("editorial ASC");
		//$statement = $select->query();
		$rowsEditorial = $tablaEditorial->fetchAll($select);
		
		if(!is_null($rowsEditorial)){
			return $rowsEditorial->toArray();
		}else{
			return null;
		}
	}
	
	public function consultaEditorial($buscar)
	{
	    $tablaEditorial = $this->tableEditorial;
	    
	    $select = $tablaEditorial->select()->from($tablaEditorial)->where('editorial LIKE %'.$consulta.'%');
	    $rows = $tableEditorial->fetchall($select);
	}
	
	public function getEditorialByParamas(array $params){
	    $tablaEditorial = $this->tableEditorial;
	    $select = $tablaEditorial->select()->from($tablaEditorial);
	    
	    if(!empty($params)){
	        foreach ($params as $key => $value) {
	            $select->where($key."=?", $value);
	        }
	    }
	    
	    //print_r($select->__toString());
	    
	    $editoriales = $tablaEditorial->fetchAll($select);
	    return $editoriales->toArray();
	    
	    
	}
 
 }
  
 
 
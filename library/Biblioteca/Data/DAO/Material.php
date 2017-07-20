<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Material{
	
	private $tableMaterial;
	
	function __construct($dbAdapter){
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableMaterial = new Biblioteca_Data_DbTable_Material(array("db"=>$dbAdapter));
	}
	
	
	public function getAllMateriales()
	{
		$tablaMaterial = $this->tableMaterial;
        $rowsMeterial = $tablaMaterial->fetchAll();
        
        if (is_null($rowsMeterial)) {
            return null;
        } else {
            return $rowsMeterial->toArray();
        }
	}

	
	
}

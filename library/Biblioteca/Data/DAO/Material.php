<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Material{
	
	private $tableMaterial;
	private $tableRecurso;
	
	function __construct($dbAdapter){
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableMaterial = new Biblioteca_Data_DbTable_Material(array("db"=>$dbAdapter));
		$this->tableRecurso = new Biblioteca_Model_DbTable_Recurso(array("db"=>$dbAdapter));
	}
	
	
	public function addMaterial($data){
	    $this->tableMaterial->insert($data);
	}
	
	
	public function getAllMateriales()
	{
		$tablaMaterial = $this->tableMaterial;
        $rowsMeterial = $tablaMaterial->fetchAll();
        
        return $rowsMeterial->toArray();
	}
	
	
	/**
	 * Get  Material in T.Material by $idMaterial
	 * @param $idMaterial
	 * @return $rowMaterial->toArray();
	 */
	
	public function getMaterialById($idMaterial) {
		$tablaMaterial = $this->tableMaterial;
		$select = $tablaMaterial->select()->from($tablaMaterial)->where("idMaterial=?",$idMaterial);
		$rowMaterial = $tablaMaterial->fetchRow($select);
		
		if (is_null($rowMaterial)) {
			return null;
		} else {
			return $rowMaterial->toArray();
		}
		
	}
	
	/**
	 * 
	 */
	public function getMaterialByIdsMaterial(array $idsMaterial) {
		$ids = array_values($idsMaterial);
		
		$tablaMaterial = $this->tableMaterial;
		$select = $tablaMaterial->select()->from($tablaMaterial)->where("idMaterial IN (?)", $ids);
		$rowsMaterial = $tablaMaterial->fetchAll($select)->toArray();
		
		return $rowsMaterial;
	}
	


	
	
}

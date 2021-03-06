<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_Material{
	
	private $tableMaterial;
	private $tableRecurso;
	
	function __construct($dbAdapter){
		$config = array('db' => $dbAdapter);
		//$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableMaterial = new Biblioteca_Data_DbTable_Material($config);
		$this->tableRecurso = new Biblioteca_Model_DbTable_Recurso($config);
	}
	
	
	public function addMaterial($data){
	    $this->tableMaterial->insert($data);
	}
	
	
	public function getAllMateriales()
	{
		$tablaMaterial = $this->tableMaterial;
        $rowsMaterial = $tablaMaterial->fetchAll();
        
        return $rowsMaterial->toArray();
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
	
	
	public function editarMaterial($idMaterial, array $datos){
	    $tM = $this->tableMaterial;
	    $where = $tM->getAdapter()->quoteInto("idMaterial=?", $idMaterial);
	    $tM->update($datos, $where);
	}
	


	
	
}

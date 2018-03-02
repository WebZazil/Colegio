<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_Subdivision{
	
	private $tableSubDivision;
	
	function __construct($dbAdapter){
		$config = array('db' => $dbAdapter);
		
		$this->tableSubDivision = new Biblioteca_Data_DbTable_Subdivision($config);
	}
	
	
	public function addSubdivision($data)
	{
		
		$this->tableSubDivision->insert($data);
		
	}
	
	
	public function getAllSubdivisiones()
	{
		$tablaSubDivision = $this->tableSubDivision;
		$select = $tablaSubDivision->select()->from($tablaSubDivision)->order("subdivision ASC");
		$rowSubdivisiones = $tablaSubDivision->fetchAll($select);
		
		if (is_null ($rowSubdivisiones)) {
			return null;
		}else{
			return $rowSubdivisiones->toArray();
		}
	}
	
	
	public function getSubdivisionById($idSubdivision)
	{
	    $tableSubdivision = $this->tableSubDivision;
        $select = $tableSubdivision->select()->from($tableSubdivision)->where("idSubdivision=?",$idSubdivision);
        $rowSubdivision = $tableSubdivision->fetchRow($select);
        
        if(is_null($rowSubdivision)){
            return null;
        }else{
            return  $rowSubdivision->toArray();
        }
	}
	
	
	public function editarSubdivision($idSubdivision, array $datos){
	    $tS = $this->tableSubDivision;
	    $where = $tS->getAdapter()->quoteInto("idSubdivision=?", $idSubdivision);
	    $tS->update($datos, $where);
	}
	
	
}

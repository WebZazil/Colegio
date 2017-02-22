<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Inventario_DAO_Municipio implements Inventario_Interfaces_IMunicipio {
	private $tablaMunicipio;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaMunicipio = new Sistema_Model_DbTable_Municipio(array('db'=>$dbAdapter));
	}
	
	public function obtenerMunicipio($idMunicipio)
	{
		$tablaMunicipio = $this->tablaMunicipio;
		$select = $tablaMunicipio->select()->from($tablaMunicipio)->where("idMunicipio = ?",$idMunicipio);
		$rowMunicipio = $tablaMunicipio->fetchRow($select);
		
		$municipioModel = new Sistema_Model_Municipio($rowMunicipio->toArray());
		$municipioModel->setIdMunicipio($rowMunicipio->idMunicipio);
		
		return $municipioModel;
	}
	
	public function obtenerMunicipios($idEstado)
	{
		$tablaMunicipio = $this->tablaMunicipio;
		$where = $tablaMunicipio->getAdapter()->quoteInto("idEstado = ?", $idEstado);
		$rowsMunicipio = $tablaMunicipio->fetchAll($where);
		
		$modelMunicipios = array();
		
		foreach ($rowsMunicipio as $rowMunicipio) {
			$modelMunicipio = new Sistema_Model_Municipio($rowMunicipio->toArray());
			//$modelMunicipio->setIdMunicipio($rowMunicipio->idMunicipio);
			//$modelMunicipio->setIdEstado($rowMunicipio->idEstado);
			$modelMunicipios[] = $modelMunicipio;
		}
		
		return $modelMunicipios;
		
	}
	public function crearMunicipio(Sistema_Model_Municipio $idMunicipio)
	{
		$tablaMunicipio = $this->tablaMunicipio;
		$tablaMunicipio->insert($municipio->toArray());
	}
	public function editarMunicipio($idMunicipio, Sistema_Model_Municipio $municipio)
	{
		$tablaMunicipio = $this->tablaMunicipio;
		$where = $tablaMunicipio->getAdapter()->quoteInto("idMunicipio = ?", $idMunicipio);
		
	}
	public function eliminarMunicipio($idMunicipio)
	{
		$tablaMunicipio = $this->tablaMunicipio;
		$where = $tablaMunicipio->getAdapter()->quoteInto("idMunicipio = ?", $idMunicipio);		
		$tablaMunicipio->delete($where);
	}
}
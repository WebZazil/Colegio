<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Inventario_DAO_Unidad implements Inventario_Interfaces_IUnidad {
	private $tablaUnidad;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaUnidad = new Inventario_Model_DbTable_Unidad(array('db'=>$dbAdapter));
	}
	
	public function obtenerUnidad($idUnidad)
	{
		$tablaUnidad = $this->tablaUnidad;
		$select = $tablaUnidad->select()->from($tablaUnidad)->where("idUnidad = ?",$idUnidad);
		$rowUnidad = $tablaUnidad->fetchRow($select);
		
		$unidadModel = new Inventario_Model_Unidad($rowUnidad->toArray());
		$unidadModel->setIdUnidad($rowUnidad->idUnidad);
		
		return $unidadModel;
	}
	
	
	public function obtenerUnidades()
	{
		$tablaUnidad = $this->tablaUnidad;
		$rowUnidades = $tablaUnidad->fetchAll();
		
		$modelUnidades = array();
		
		foreach ($rowUnidades as $rowUnidad) {
			$modelUnidad = new Inventario_Model_Unidad($rowUnidad->toArray());
			$modelUnidad->setIdUnidad($rowUnidad->idUnidad);
			
			$modelUnidades[] = $modelUnidad;
		}
		
		return $modelUnidades;	
	}
	
	public function crearUnidad( Inventario_Model_Unidad $unidad)
	{
		$tablaUnidad = $this->tablaUnidad;
		$select = $tablaUnidad->select()->from($tablaUnidad);
		$rowUnidad = $tablaUnidad->fetchRow($select);
		//print_r("<br />");
		//print_r($row->toArray());
		//if(!is_null($row)) throw new Util_Exception_BussinessException("Unidad: <strong>" . $unidad->getUnidad() . "</strong> duplicado en el sistema");
		//$unidad->setHash($unidad->getHash());
		
		$tablaUnidad->insert($unidad->toArray());
	
	}
	

	public function editarUnidad($idUnidad, array $unidad)
	{							
		$tablaUnidad = $this->tablaUnidad;
		$where = $tablaUnidad->getAdapter()->quoteInto("idUnidad = ?", $idUnidad);
		$tablaUnidad->update($unidad, $where);
		
		
	}
		
	public function eliminarUnidad($idUnidad)
	{
		$tablaUnidad = $this->tablaUnidad;
		$where = $tablaUnidad->getAdapter()->quoteInto("idUnidad = ?", $idUnidad);		
		$tablaUnidad->delete($where);
	}
	
	
}
<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 * */
class Inventario_DAO_Multiplo implements Inventario_Interfaces_IMultiplo {
	private $tablaMultiplo;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaMultiplo= new Inventario_Model_DbTable_Multiplos(array('db'=>$dbAdapter));
		
	}
	public function obtenerUnidades($idUnidad)
	{
		$tablaMultiplo = $this->tablaMultiplo;
		$where = $tablaMultiplo->getAdapter()->quoteInto("idUnidad = ?", $idUnidad);
		$rowsMultiplo = $tablaMultiplo->fetchAll($where);
		
		$modelMultiplos = array();
		
		foreach ($rowsMultiplo as $rowMultiplo) {
			$modelMultiplo = new Inventario_Model_Multiplos($rowMultiplo->toArray());
			$modelMultiplo[] = $modelMultiplo;
		}
		return $modelMultiplos;
	}
	
	public function obtenerMultiplo($idMultiplos)
	{
		$tablaMultiplo = $this->tablaMultiplo;
		$select = $tablaMultiplo->select()->from($tablaMultiplo)->where("idMultiplos = ?",$idMultiplos);
		$rowMultiplo = $tablaMultiplo->fetchRow($select);
		
		$multiploModel = new Inventario_Model_Multiplos($rowMultiplo->toArray());
		$multiploModel->setIdMultiplos($rowMultiplo->idMultiplos);
		
		return $multiploModel;
	}
	
	public function obtenerMultiplos($idProducto)
	{
		$tablaMultiplo = $this->tablaMultiplo;
		$where = $tablaMultiplo->getAdapter()->quoteInto("idProducto = ?", $idProducto);
		$rowsMultiplo = $tablaMultiplo->fetchAll($where);
		
		$modelMultiplos = array();
		foreach ($rowsMultiplo as $rowMultiplo) {
			$modelMultiplo = new Inventario_Model_Multiplos($rowMultiplo->toArray());
			$modelMultiplos[] = $modelMultiplo;
		}
		
		return $modelMultiplos;
		
		print_r($where);
		
	}

	public function crearMultiplos(Inventario_Model_Multiplos $multiplo)
	{
		$this->tablaMultiplo->insert($multiplo->toArray());		
	}
	
	public function editarMultiplo($idMultiplo, array $datos)
	{
		$tablaMultiplo = $this->tablaMultiplo;
		$where = $tablaMultiplo->getAdapter()->quoteInto("idMultiplos = ?", $idMultiplo);
		$tablaMultiplo->update($datos, $where);

	}
	
	public function eliminarMultiplo($idMultiplos)
	{
		
	}
	
	
}
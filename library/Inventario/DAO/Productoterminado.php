<?php
class Inventario_DAO_Productoterminado implements Inventario_Interfaces_IProductoterminado
{
	private $tablaProductoCompuesto;
	private $tablaProducto;
	private $tablaMultiplos;
	
	private $tablaInventario;
	
	public function __construct()
	{
		$this->tablaProducto = new Inventario_Model_DbTable_Producto;
		$this->tablaInventario = new Inventario_Model_DbTable_Inventario;
		
	}
	public function obtenerProducto()
	{
		$tablaProducto = $this->tablaProducto;
		$select = $tablaProducto->select()->setIntegrityCheck(false)
		->from($tablaProducto, array('claveProducto','producto'))
		->join('Inventario','Producto.idProducto = Inventario.idProducto', array())
		->where('Inventario.idSucursal=2')
		->order('producto ASC');
		
		return $tablaProducto->fetchAll($select);
	}
	
	
	public function obtenerProductoTerminado()
	{
		$tablaProducto = $this->tablaProducto;
		$select = $tablaProducto->select()->from($tablaProducto)->where('claveProducto like?','PTVT%')
		->order("producto");
		$rowProductos = $tablaProducto->fetchAll($select);
		
		$modelProductos = array();
		
		foreach ($rowProductos as $rowProducto) {
			$modelProducto = new Inventario_Model_Producto($rowProducto->toArray());
			$modelProducto->setIdProducto($rowProducto->idProducto);
			
			$modelProductos[] = $modelProducto;
			
		}
		
		return $modelProductos;
	}
}

<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Inventario_DAO_Inventario implements Inventario_Interfaces_IInventario {
	
	private $tablaInventario;
	private $tablaProducto;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaInventario = new Inventario_Model_DbTable_Inventario(array('db'=>$dbAdapter));
		$this->tablaProducto = new Inventario_Model_DbTable_Producto(array('db'=>$dbAdapter));
	}
	
	public function obtenerInventario(){
		$tablaInventario = $this->tablaInventario;
		$rowsInventario = $tablaInventario->fetchAll();
		
		$modelInventario = array();
		
		foreach($rowsInventario as $rowInventario){
			$modelInventario = new Inventario_Model_Inventario($rowInventario->toArray());
			$modelInventario->setIdInventario($rowInventario->idInventario);
			
			$modelsInventario [] = $modelInventario;
			
		}
		
		return $modelsInventario;
	}
	
public function obtenerIdProductoInventario(){ 
		$tablaProducto = $this->tablaProducto;
		$tablaInventario = $this->tablaInventario;
		
		$rowsInventario = $tablaInventario->fetchAll();
		//$inventario = $rowsInventario;
		
		$idProducto = array();
		
		foreach ($rowsInventario as $row){
			$select = $tablaProducto->select()->from($tablaProducto)->where("idProducto = ?",$row->idProducto);
			$rowProducto= $tablaProducto->fetchRow($select);
			
			$idProducto[] = $rowProducto->idProducto;
		}
		return $idProducto;
	}
	
	public function obtenerProductoInventario($idInventario){
		$tablaInventario = $this->tablaInventario;
		$select = $tablaInventario->select()->from($tablaInventario)->where("idInventario = ?", $idInventario);
		$rowInventario = $tablaInventario->fetchRow($select);
		
		$inventarioModel = new Inventario_Model_Inventario($rowInventario->toArray());
		$inventarioModel->setIdInventario($rowInventario->idInventario);
		
		return $inventarioModel;
		
	}
	
	public function editarInventario($idInventario, array $inventario)
	{
		$bd = Zend_Db_Table_Abstract::getDefaultAdapter();
		$bd->beginTransaction();

		try{
			
			$tablaInventario = $this->tablaInventario;
			$select = $tablaInventario->select()->from($tablaInventario)->where("idInventario=?",$idInventario);
			$row = $tablaInventario->fetchRow($select);
			$where = $tablaInventario->getAdapter()->quoteInto("idInventario = ?", $idInventario);
			
		if(!is_null($row)){
			
			$costoCliente = 0;
			$porcentajeGanancia = 0;
			$minimo = $inventario['minimo'];
			$maximo = $inventario['maximo'];
			$costoUnitario = $inventario['costoUnitario'];
			$cantidadGanancia = $inventario['cantidadGanancia'];
			print_r("<br />");print_r("<br />");
			//print_r($cantidadGanancia);
			$cantGanancia=$row->cantidadGanancia;
			print_r("<br />");print_r("<br />");
			//print_r($cantGanancia);
			
			if($cantGanancia !== $cantidadGanancia){
				$costoCliente = $inventario['costoUnitario'] + $inventario['cantidadGanancia'];
				//print_r("<br />");print_r("<br />");
				//print_r($costoCliente);
				$inventario['costoCliente']= $costoCliente;
				$porcentajeGanancia = ((($inventario['costoCliente'] / $inventario['costoUnitario']) -1 ) * 100);
				print_r("<br />");print_r("<br />");
				print_r($inventario['costoCliente']);
				print_r("<br />");print_r("<br />");
				print_r($inventario['costoUnitario']);
				print_r("<br />");print_r("<br />");
				print_r($porcentajeGanancia);
				
				//print_r($porcentajeGanancia);
			}else{
			//print_r($porcentajeGanancia);
				$costoCliente = ($costoUnitario * $inventario['porcentajeGanancia'] / 100) + $costoUnitario + $cantidadGanancia;	
				//print_r("<br />");print_r("<br />");
				//print_r($costoUnitario);
				//print_r("<br />");print_r("<br />");
				print_r($costoCliente);
				//print_r("<br />");print_r("<br />");
				//print_r($costoCliente);
			
			}
		}
		
		$tablaInventario->update(array('minimo'=>$minimo, 'maximo'=>$maximo, 'costoUnitario'=>$costoUnitario, 
		'cantidadGanancia'=>$cantidadGanancia,'porcentajeGanancia'=>$porcentajeGanancia,'costoCliente'=>$costoCliente),$where);		
		$bd->commit();
		}catch(exception $ex){
			print_r("<br />");
			print_r("================");
			print_r("<br />");
			print_r("Excepcion Lanzada");
			print_r("<br />");
			print_r("================");
			print_r("<br />");
			print_r($ex->getMessage());
			print_r("<br />");
			print_r("<br />");
			$bd->rollBack();
		
		}
		
	}

	public function editarTodo(array $inventario){
			
		$bd = Zend_Db_Table_Abstract::getDefaultAdapter();
		$bd->beginTransaction();
			
		$minimo = $inventario['minimo'];
		$maximo = $inventario['maximo'];
		$porcentejeGanancia = $inventario['porcentajeGanancia'];
		
		//print_r($minimo);
		
		$tablaInventario = $this->tablaInventario;
		
			$select = $tablaInventario->select()->from($tablaInventario);
			$row = $tablaInventario->fetchAll();
			$where = $tablaInventario->getAdapter()->quoteInto("minimo >= ?", 0);
			//print_r($where);
		
		$tablaInventario->update (array('minimo'=>$minimo,'maximo'=>$maximo),$where);
		
		//======================================Editar porcentaje Ganancia
		
		if ($porcentejeGanancia<>0){
			print_r("Hey!");
			
			$select = $bd->select()->from('Inventario','costoUnitario');
			$rowProductos = $select->query()->fetchAll();
			//print_r("$rowProductos");
			$costoUnitario= $rowProductos->costoUnitario;
			
			//$where = $tablaInventario->getAdapter()->quoteInto("minimo >= ?", 0);
			
			//return $tablaInventario->fetchAll($select);
			
			/*if(!is_null($rowProductos)){
				print_r("Hola!");
				$costoUnitario= $select->costoUnitario;
				$cantidad = ($costoUnitario * $porcentejeGanancia/100);
				$precio = ($costoUnitario * $porcentejeGanancia/100)+ $costoUnitario;
				
				print_r($costoUnitario);
				$tablaInventario->update (array('porcentajeGanancia'=>$porcentejeGanancia,'cantidadGanancia'=>$cantidad, 'costoCliente'=>$precio),$where);
			}*/
		}
	}
}

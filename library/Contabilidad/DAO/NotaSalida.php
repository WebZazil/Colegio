<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Conslaultores S.A. de C.V.
 * @version 1.0.0
 */
class Contabilidad_DAO_NotaSalida implements Contabilidad_Interfaces_INotaSalida{
	private $tablaMovimiento;
	private $tablaInventario;
	private $tablaCapas;
	private $tablaMultiplos;
	private $tablaCardex;
	private $tablaClientes;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaMovimiento = new Contabilidad_Model_DbTable_Movimientos(array('db'=>$dbAdapter));
		$this->tablaCapas = new Contabilidad_Model_DbTable_Capas(array('db'=>$dbAdapter));
		$this->tablaInventario = new Contabilidad_Model_DbTable_Inventario(array('db'=>$dbAdapter));
		$this->tablaMultiplos = new Inventario_Model_DbTable_Multiplos(array('db'=>$dbAdapter));
		$this->tablaCardex = new  Contabilidad_Model_DbTable_Cardex(array('db'=>$dbAdapter));
		$this->tablaEmpresa = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));	
	}
	
	public function obtenerClientes(){
		$tablaEmpresa = $this->tablaEmpresa;
		$select=$tablaEmpresa->select()
		->setIntegrityCheck(false)
		->from($tablaEmpresa, array('idEmpresa'))
		->join('Fiscales', 'Empresa.idFiscales = Fiscales.idFiscales', array('razonSocial'))
		->join('Clientes','Empresa.idEmpresa = Clientes.idEmpresa')
		->order("razonSocial ASC");
		return $tablaEmpresa->fetchAll($select);	
	}

	
	public function guardaMovimientos(array $encabezado, $producto){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
		
		$dateIni = new Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
		
		/*$tablaMultiplos = $this->tablaMultiplos;
		$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
		$multiplos = $tablaMultiplos->fetchRow($select); 
		//print_r($row);
		if(is_null($multiplos)) throw new Util_Exception_BussinessException("Error: Favor de verificar Multiplo");
		*/
		try{
			//=======================Crea secuencial, convierte a unidad minima y guarda registro en tabla Movimiento=======================================
			$tablaMovimiento = $this->tablaMovimiento;
			$select = $tablaMovimiento->select()->from($tablaMovimiento)->where("numeroFolio=?",$encabezado['numFolio'])
			->where("idTipoMovimiento=?",$encabezado['idTipoMovimiento'])
			->where("idCoP=?",$encabezado['idCoP'])
			->where("idEmpresas=?",$encabezado['idEmpresas'])
			->where("fecha=?", $stringIni)
			->order("secuencial DESC");
	
			$rowMovimiento = $tablaMovimiento->fetchRow($select); 
		
			if(!is_null($rowMovimiento)){
				$secuencial= $rowMovimiento->secuencial +1;
			}else{
				$secuencial = 1;	
			}

			//==================================================================
			$tablaMultiplos = $this->tablaMultiplos;
			$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
			$rowMultiplos = $tablaMultiplos->fetchRow($select); 
			//print_r("$select");
			$cantidad = 0;
			$precioUnitario = 0;
			
			$cantidad = $producto['cantidad'] * $rowMultiplos->cantidad;
			$precioUnitario = $producto['precioUnitario'] / $rowMultiplos->cantidad;
			
			$mMovtos = array(
					'idProducto' => $producto['descripcion'],
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idEmpresas'=>$encabezado['idEmpresas'],
					'idSucursal'=>$encabezado['idSucursal'],
					'idCoP'=>$encabezado['idCoP'],
					//'idProyecto'=>$encabezado['idProyecto'],
					'numeroFolio'=>$encabezado['numFolio'],
					'cantidad'=>$cantidad,
					'fecha'=>$stringIni,
					'estatus'=>"A",
					'secuencial'=> $secuencial,
					'costoUnitario'=>$precioUnitario,
					'totalImporte'=>$producto['importe']
				);
			//print_r($mMovtos);
			$dbAdapter->insert("Movimientos",$mMovtos);
			$dbAdapter->commit();
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
			$dbAdapter->rollBack();
		}
		
	}
	
	public function resta(array $encabezado, $producto){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
		$dateIni = new Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
		
		try{
		//======================Resta en capas, inventario============================================================================*//
		$tablaMultiplos = $this->tablaMultiplos;
		$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
		$rowMultiplo = $tablaMultiplos->fetchRow($select); 
		
		$cantidad = 0;
		$precioUnitario = 0;
		$cantidad = $producto['cantidad'] * $rowMultiplo->cantidad;
		$precioUnitario = $producto['precioUnitario'] / $rowMultiplo->cantidad;
			
			
		$tablaInventario = $this->tablaInventario;
		$select = $tablaInventario->select()->from($tablaInventario)->where("idProducto=?",$producto['descripcion']);
		$rowInventario = $tablaInventario->fetchRow($select);
		$restaCantidad = $rowInventario->existencia - $cantidad;
		
		//print_r("Cantidad en inventario:");
		//print_r("$restaCantidad");
		

		if(!is_null($rowInventario)){
			//print_r("la cantidad en inventario no es menor que 0");
			print_r("<br />");
			$tablaCapas = $this->tablaCapas;
			$select = $tablaCapas->select()->from($tablaCapas)->where("idProducto=?",$producto['descripcion']) 
			->order("fechaEntrada ASC");
			$rowCapas = $tablaCapas->fetchRow($select);
			//print_r("<br />");
			print_r("<br />");
			//print_r("$select");
			//print_r("<br />");
			$cant =  $rowCapas->cantidad - $cantidad;
			//print_r("Cant <br />");
			print_r("<br />");
			//print_r("<Cantidad en Capas />");
			print_r("<br />");
			//print_r($cant);
			print_r("<br />");
			
			
		//=====================================================================Resta 
		if(!$cant <= 0){
			
			$where = $tablaCapas->getAdapter()->quoteInto("idProducto =?",$rowCapas->idProducto,"fechaEntrada =?",$rowCapas->fechaEntrada );
			print_r("<br />");
			//print_r("query seleccion producto:");		
			print_r("<br />");
			//print_r("$where");
			print_r("<br />");
			//print_r($cant);
			print_r("<br />");
			$tablaCapas->update(array('cantidad'=>$cant), $where);
			print_r("<br />");
			print_r("<br />");
			//print_r("$where");
		}else{
		
			$where = $tablaCapas->getAdapter()->quoteInto("fechaEntrada=?", $rowCapas->fechaEntrada,"idProducto =?",$rowCapas->idProducto);	
			$tablaCapas->delete($where);
		}
		
		//===Resta cantidad en inventario
		
			$tablaInventario = $this->tablaInventario;
			$where = $tablaInventario->getAdapter()->quoteInto("idProducto=?", $producto['descripcion']);
			$tablaInventario->update(array('existencia'=>$restaCantidad, 'existenciaReal'=>$restaCantidad),$where);
		}
		$dbAdapter->commit();
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
			$dbAdapter->rollBack();
		}
	}
	public function creaCardex(array $encabezado, $producto){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
		
		$dateIni = new Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
		try{
		$tablaCapas = $this->tablaCapas;
		$select = $tablaCapas->select()->from($tablaCapas)->where("idProducto=?",$producto['descripcion'])
		//->where("fechaEntrada )
		->order("fechaEntrada ASC");
		$rowCapas = $tablaCapas->fetchRow($select);
		
		//=======================================Seleccionar tabla Movimiento
		$tablaMovimiento = $this->tablaMovimiento;
			$select = $tablaMovimiento->select()->from($tablaMovimiento)->where("numeroFolio=?",$encabezado['numFolio'])
			->where("idTipoMovimiento=?",$encabezado['idTipoMovimiento'])
			->where("idCoP=?",$encabezado['idCoP'])
			->where("idEmpresas=?",$encabezado['idEmpresas'])
			->where("fecha=?", $stringIni)
			->order("secuencial DESC");
		$rowMovimiento = $tablaMovimiento->fetchRow($select); 
		
		$tablaMultiplos = $this->tablaMultiplos;
		$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
		$rowMultiplo = $tablaMultiplos->fetchRow($select); 
		$utilidad = ($rowMovimiento->costoUnitario - $rowCapas['costoUnitario'])* $rowMovimiento->cantidad;
		//print_r($utilidad);
		$cantidad = 0;
		$precioUnitario = 0;
		$cantidad = $producto['cantidad'] * $rowMultiplo->cantidad;
		$precioUnitario = $producto['precioUnitario'] / $rowMultiplo->cantidad;
		
		$mCardex = array(
					'idSucursal'=>$encabezado['idSucursal'],
					'numerofolio'=>$encabezado['numFolio'],
					'idProducto'=>$producto['descripcion'],
					'idDivisa'=>1,
					'secuencialEntrada'=>$rowCapas['secuencial'],
					'fechaEntrada'=>$rowCapas['fechaEntrada'],
					'secuencialSalida'=>$rowMovimiento->secuencial,
					'fechaSalida'=>$stringIni,
					'cantidad'=>$cantidad,
					'costo'=>$rowCapas['costoUnitario'],
					'costoSalida'=>$producto['importe'],
					'utilidad'=>$utilidad
					
			);
			//print_r($mCardex);
			$dbAdapter->insert("Cardex",$mCardex);
			$dbAdapter->commit();
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
			$dbAdapter->rollBack();
		}
	}
}
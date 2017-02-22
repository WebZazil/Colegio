<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Contabilidad_DAO_RemisionSalida implements Contabilidad_Interfaces_IRemisionSalida{
	private $tablaMovimiento;
	private $tablaInventario;
	private $tablaCapas;
	private $tablaMultiplos;
	private $tablaCardex;
	private $tablaClientes;
	private $tablaCuentasxc;
	private $tablaBancos;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaMovimiento = new Contabilidad_Model_DbTable_Movimientos(array('db'=>$dbAdapter));
		$this->tablaCapas = new Contabilidad_Model_DbTable_Capas(array('db'=>$dbAdapter));
		$this->tablaInventario = new Contabilidad_Model_DbTable_Inventario(array('db'=>$dbAdapter));
		$this->tablaMultiplos = new Inventario_Model_DbTable_Multiplos(array('db'=>$dbAdapter));
		$this->tablaCardex = new  Contabilidad_Model_DbTable_Cardex(array('db'=>$dbAdapter));
		$this->tablaEmpresa = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));	
		$this->tablaClientes =  new Sistema_Model_DbTable_Clientes(array('db'=>$dbAdapter));
		$this->tablaCuentasxc = new Contabilidad_Model_DbTable_Cuentasxc(array('db'=>$dbAdapter));
		$this->tablaBancos = new Contabilidad_Model_DbTable_Banco(array('db'=>$dbAdapter));
	}
	
	public function obtenerClientes(){
		$tablaEmpresa = $this->tablaEmpresa;
		$select=$tablaEmpresa->select()
		->setIntegrityCheck(false)
		->from($tablaEmpresa, array('idEmpresa'))
		->join('fiscales', 'Empresa.idFiscales = fiscales.idFiscales', array('razonSocial'))
		->join('Clientes','Empresa.idEmpresa = Clientes.idEmpresa');
		return $tablaEmpresa->fetchAll($select);	
	}

	
	public function restarProducto(array $encabezado, $producto, $formaPago){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();	
		//$bd = Zend_Db_Table_Abstract::getDefaultAdapter();
		//$bd->beginTransaction();
		
		
		$date = new Zend_Date();
		$dateIni = new Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
		
		$tablaMultiplos = $this->tablaMultiplos;
		$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
		$row = $tablaMultiplos->fetchRow($select); 
		
		if(is_null($row)) throw new Util_Exception_BussinessException("Error: Favor de verificar ");
		
		try{
			$secuencial=0;	
			$tablaMovimiento = $this->tablaMovimiento;
			$select = $tablaMovimiento->select()->from($tablaMovimiento)->where("numeroFolio=?",$encabezado['numFolio'])
			->where("idCoP=?",$encabezado['idCoP'])
			->where("idSucursal=?",$encabezado['idSucursal'])
			->where("fecha=?", $stringIni)
			->order("secuencial DESC");
		
			$row = $tablaMovimiento->fetchRow($select); 
		
			if(!is_null($row)){
				$secuencial= $row->secuencial +1;
			}else{
				$secuencial = 1;	
			}
			//=================Selecciona producto y unidad=======================================
			$tablaMultiplos = $this->tablaMultiplos;
			$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
			$row = $tablaMultiplos->fetchRow($select); 
		
			//====================Operaciones para convertir unidad minima====================================================== 
			$cantidad=0;
			$precioUnitario=0;
			$cantidad = $producto['cantidad'] * $row->cantidad;
			$precioUnitario = $producto['precioUnitario'] / $row->cantidad;
			
			$mMovtos = array(
					'idProducto' => $producto['descripcion'],
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idEmpresas'=>$encabezado['idEmpresas'],
					'idSucursal'=>$encabezado['idSucursal'],
					'idCoP'=>$encabezado['idCoP'],
					'idProyecto'=>$encabezado['idProyecto'],
					//'idFactura'=>0,
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
			
			$secuencial = 0;	
			$tablaCuentasxc = $this->tablaCuentasxc;
			$select = $tablaCuentasxc->select()->from($tablaCuentasxc)->where("numeroFolio=?",$encabezado['numFolio'])
			->where("idCoP=?",$encabezado['idCoP'])
			->where("idSucursal=?",$encabezado['idSucursal'])
			->where("fechaPago=?", $stringIni)
			->order("secuencial DESC");
			
			$row = $tablaMovimiento->fetchRow($select); 
			
			if(!is_null($row)){
				$secuencial= $row->secuencial +1;
			}else{
				$secuencial = 1;	
			}
			
			$mCuentasxc = array(
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idSucursal'=>$encabezado['idSucursal'],
					//'idFactura'=>$encabezado['idFactura'],
					'idCoP'=>$encabezado['idCoP'],
					'idBanco'=>$formaPago['idBanco'],
					'idDivisa'=>$formaPago['idDivisa'],
					'idsImpuestos'=>0,
					'numeroFolio'=>$encabezado['numFolio'],
					'secuencial'=>$secuencial,
					'fechaCaptura'=>$date->toString ('yyyy-MM-dd'),
					'fechaPago'=>$stringIni,
					'estatus'=>"A",
					'numeroReferencia'=>0,
					
					'formaLiquidar'=>$formaPago['formaLiquidar'],
					'conceptoPago'=>$formaPago['conceptoPago'],
					'subTotal'=>0,
					'total'=>$producto['importe']
				);   
			
				$dbAdapter->insert("Cuentasxc",$mCuentasxc);
			
		//======================Resta en capas, inventario y crea Cardex============================================================================*//
		$tablaMultiplos = $this->tablaMultiplos;
		$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
		$row = $tablaMultiplos->fetchRow($select); 
		
		$cantidad = 0;
		$precioUnitario = 0;
		$cantidad = $producto['cantidad'] * $row->cantidad;
		$precioUnitario = $producto['precioUnitario'] / $row->cantidad;
			
			
		$tablaInventario = $this->tablaInventario;
		$select = $tablaInventario->select()->from($tablaInventario)->where("idProducto=?",$producto['descripcion']);
		$rowInventario = $tablaInventario->fetchRow($select);
		$restaCantidad = $rowInventario->existencia - $cantidad;
		
		//print_r("Cantidad en inventario:");
		//print_r("$restaCantidad");
		

		if(!is_null($row)){
			print_r("la cantidad en inventario no es menor que 0");
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
			
			$where = $tablaCapas->getAdapter()->quoteInto("idProducto =?",$row->idProducto);
			print_r("<br />");
			print_r("query seleccion producto:");		
			print_r("<br />");
			print_r("$where");
			print_r("<br />");
			print_r($cant);
			print_r("<br />");
			$tablaCapas->update(array('cantidad'=>$cant), $where);
			print_r("<br />");
			print_r("<br />");
			print_r("$where");
		}else{
		
			$where = $tablaCapas->getAdapter()->quoteInto("fechaEntrada=?", $row->fechaEntrada );	
			$tablaCapas->delete($where);
		}
		$tablaCapas = $this->tablaCapas;
		$select = $tablaCapas->select()->from($tablaCapas)->where("idProducto=?",$producto['descripcion'])
		//->where("fechaEntrada )
		->order("fechaEntrada ASC");
		$row = $tablaCapas->fetchRow($select);
		
		$mCardex = array(
					'idSucursal'=>$encabezado['idSucursal'],
					'numerofolio'=>$encabezado['numFolio'],
					'idProducto'=>$producto['descripcion'],
					'idDivisa'=>1,
					'secuencialEntrada'=>$row['secuencial'],
					'fechaEntrada'=>$row['fechaEntrada'],
					'secuencialSalida'=>$mMovtos['secuencial'],
					'fechaSalida'=>$stringIni,
					'cantidad'=>$cantidad,
					'costo'=>$row['costoUnitario'],
					'costoSalida'=>$producto['importe'],
					'utilidad'=>($mMovtos['costoUnitario']-$row['costoUnitario'])* $mMovtos['cantidad']
					
			);
			//print_r($mCardex);
			$dbAdapter->insert("Cardex",$mCardex);
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
	
	public function editarBanco($formaPago ,$productos){
		$tablaBancos = $this->tablaBancos;
		$where = $tablaBancos->getAdapter()->quoteInto("idBanco= ?", $formaPago['idBanco']);
		$rowBanco = $tablaBancos->fetchRow($where);
		$importePago = $rowBanco->saldo - $productos[0]['importe'];
		$tablaBancos->update(array('saldo'=> $importePago), $where);
	}
}
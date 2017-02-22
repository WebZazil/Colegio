<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Contabilidad_DAO_RemisionEntrada implements Contabilidad_Interfaces_IRemisionEntrada{	
	private $tablaMovimiento;
	private $tablaInventario;
	private $tablaCapas;
	private $tablaMultiplos;
	private $tablaEmpresa;
	private $tablaBanco;
	
	private $tablaCuentasxp;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaMovimiento = new Contabilidad_Model_DbTable_Movimientos(array('db'=>$dbAdapter));
		$this->tablaCapas = new Contabilidad_Model_DbTable_Capas(array('db'=>$dbAdapter));
		$this->tablaInventario = new Contabilidad_Model_DbTable_Inventario(array('db'=>$dbAdapter));
		$this->tablaMultiplos = new Inventario_Model_DbTable_Multiplos(array('db'=>$dbAdapter));
		$this->tablaEmpresa = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));
		$this->tablaCuentasxp = new Contabilidad_Model_DbTable_Cuentasxp(array('db'=>$dbAdapter));
		$this->tablaBanco = new Contabilidad_Model_DbTable_Banco(array('db'=>$dbAdapter));
		$this->tablaProducto = new Inventario_Model_DbTable_Producto(array('db'=>$dbAdapter));
	}
	
	public function obtenerProveedores(){
		$tablaEmpresa = $this->tablaEmpresa;
		$select=$tablaEmpresa->select()
		->setIntegrityCheck(false)
		->from($tablaEmpresa, array('idEmpresa'))
		->join('fiscales', 'Empresa.idFiscales = fiscales.idFiscales', array('razonSocial'))
		->join('Proveedores','Empresa.idEmpresa = Proveedores.idEmpresa');
		return $tablaEmpresa->fetchAll($select);	
	}
	
	public function agregarProducto(array $encabezado, $producto, $formaPago){
		
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
		$dateIni = new Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
		
		try{
			$secuencial=0;	
			$tablaMovimiento = $this->tablaMovimiento;
			$select = $tablaMovimiento->select()->from($tablaMovimiento)->where("numeroFolio=?",$encabezado['numFolio'])
			->where("idCoP=?",$encabezado['idCoP'])
			->where("idSucursal=?",$encabezado['idSucursal'])
			->where("fecha=?", $stringIni)
			->order("secuencial DESC");
			$rowMovimiento = $tablaMovimiento->fetchRow($select); 
		
			if(!is_null($rowMovimiento)){
				$secuencial= $row->secuencial +1;
			}else{
				$secuencial = 1;	
			}
			//=================Selecciona producto y unidad=======================================
			$tablaMultiplos = $this->tablaMultiplos;
			$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
			$rowMultiplo = $tablaMultiplos->fetchRow($select); 
		
			//====================Operaciones para convertir unidad minima====================================================== 
			$cantidad=0;
			$precioUnitario=0;
			$cantidad = $producto['cantidad'] * $rowMultiplo->cantidad;
			$precioUnitario = $producto['precioUnitario'] / $rowMultiplo->cantidad;
			
			$mMovtos = array(
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idEmpresas'=>$encabezado['idEmpresas'],
					'idSucursal'=>$encabezado['idSucursal'],
					'idCoP'=>$encabezado['idCoP'],
					'numeroFolio'=>$encabezado['numFolio'],
					//'idFactura'=>0,
					'idProducto' => $producto['descripcion'],
					'idProyecto'=>$encabezado['idProyecto'],
					'cantidad'=>$cantidad,
					'fecha'=>$stringIni,
					'secuencial'=> $secuencial,
					'estatus'=>"A",
					'costoUnitario'=>$precioUnitario,
					'totalImporte'=>$producto['importe']
				);
			
			//print_r($mMovtos);
			$dbAdapter->insert("Movimientos",$mMovtos);	
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
	
	public function guardaPago (array $encabezado, $formaPago,$productos){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
		//$fecha = date('Y-m-d h:i:s', time());
		$dateIni = new Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
	
		try{
			$secuencial=0;	
			$tablaCuentasxp = $this->tablaCuentasxp;
			$select = $tablaCuentasxp->select()->from($tablaCuentasxp)->where("numeroFolio=?",$encabezado['numFolio'])
			->where("idCoP=?",$encabezado['idCoP'])
			->where("idSucursal=?",$encabezado['idSucursal'])
			->where("fechaPago=?", $stringIni)
			->order("secuencial DESC");
			$rowCuentasxp = $tablaCuentasxp->fetchRow($select); 
			
			if(!is_null($rowCuentasxp)){
				$secuencial= $row->secuencial +1;
			}else{
				$secuencial = 1;	
			}
			
			$mCuentasxp = array(
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idSucursal'=>$encabezado['idSucursal'],
					'idCoP'=>$encabezado['idCoP'],
					//'idFactura'=>$encabezado['idFactura'],
					'idBanco'=>$formaPago['idBanco'],
					'idDivisa'=>$formaPago['idDivisa'],
					'numeroFolio'=>$encabezado['numFolio'],
					'numeroReferencia'=>"",
					'secuencial'=>$secuencial,
					'estatus'=>"A",
					'fechaPago'=>$stringIni,
					'fechaCaptura'=>date('Y-m-d h:i:s', time()),
					'formaLiquidar'=>$formaPago['formaLiquidar'],
					'conceptoPago'=>$formaPago['conceptoPago'],
					'subTotal'=>0,
					'total'=>$productos[0]['importe']
				);   
				
				//print_r($mCuentasxp);
				$dbAdapter->insert("Cuentasxp",$mCuentasxp);
			
			//========================Realiza Movimiento en banco===================================	
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
		$tablaBanco = $this->tablaBanco;
			//$select = $tablaBanco->select()->from($tablaBanco)->where("idBanco =?", $formaPago['idBanco']);
			$where = $tablaBanco->getAdapter()->quoteInto("idBanco = ?", $formaPago['idBanco']);
			$rowBanco = $tablaBanco->fetchRow($where);
		
			//if(!is_null($rowBanco)){
				print_r("La consulta no esta vacia");
				$importePago = $rowBanco->saldo - $productos[0]['importe'];
				print_r("<br />");
				print_r($importePago);
				//$tablaBanco->update(array('saldo'=> $importePago), $where);
				$tablaBanco->update(array('saldo'=> $importePago), $where);
			//}
			
	}	
	
}


<?php
class Contabilidad_DAO_FacturaCliente implements Contabilidad_Interfaces_IFacturaCliente{
	private $tablaMovimiento;
	private $tablaFactura;
	private $tablaClientes;
	private $tablaCuentasxc;
	private $tablaBanco;
	private $tablaEmpresas;
	
	private $tablaEmpresa;
	private $tablaImpuestoProductos;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaMovimiento = new Contabilidad_Model_DbTable_Movimientos(array('db'=>$dbAdapter));
		//$this->tablaFactura = new Contabilidad_Model_DbTable_Factura(array('db'=>$dbAdapter));
		$this->tablaFactura = new Contabilidad_Model_DbTable_Factura(array('db'=>$dbAdapter));
		$this->tablaClientes = new Sistema_Model_DbTable_Clientes(array('db'=>$dbAdapter));
		$this->tablaCuentasxc = new Contabilidad_Model_DbTable_Cuentasxc(array('db'=>$dbAdapter));
		$this->tablaBanco = new Contabilidad_Model_DbTable_Banco(array('db'=>$dbAdapter));
		$this->tablaEmpresas = new Sistema_Model_DbTable_Empresas(array('db'=>$dbAdapter));
		$this->tablaEmpresa = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));
		
		
		$this->tablaImpuestoProductos = new Contabilidad_Model_DbTable_ImpuestoProductos(array('db'=>$dbAdapter));
	}
	
	
	public function guardaFactura(array $encabezado, $importe, $formaPago, $productos){
		
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$dbAdapter->beginTransaction();
		
		$fechaInicio = new Zend_Date($encabezado['fecha'],'YY-mm-dd');
		$stringFecha = $fechaInicio->toString('YY-mm-dd');
	
		try{
			//Valida que la factura no exista
			$tablaFactura = $this->tablaFactura;
			$select = $tablaFactura->select()->from($tablaFactura)->where("idTipoMovimiento = ?",$encabezado['idTipoMovimiento'])->where("numeroFactura=?",$encabezado['numeroFactura'])
			->where("idCoP=?",$encabezado['idCoP'])->where("idSucursal=?",$encabezado['idSucursal']);
			$rowFactura = $tablaFactura->fetchRow($select);
			//print_r("$select");
			
			if(! is_null($rowFactura)){
				print_r("La Factura Ya existe");
			 }else{
				print_r("Puede crear Factura");
				$conceptoPago;
				if(($formaPago['Pagada'])==="1"){
					$conceptoPago = "LI";
				}elseif(($formaPago['Pagada'])=== "0"){
					$conceptoPago = "PA";
				}elseif($formaPago['pagos']===""){
					$conceptoPago = "PE";
				}	
				//Guarda Movimiento en tabla factura
				$mFactura = array(
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idSucursal'=>$encabezado['idSucursal'],
					'idCoP'=>$encabezado['idCoP'],
					'idDivisa'=>$formaPago['idDivisa'],
					'numeroFactura'=>$encabezado['numeroFactura'],
					'estatus'=>"A",
					'conceptoPago'=>$conceptoPago,
					'descuento'=>100,
					'formaPago'=>$formaPago['formaLiquidar'],
					'fechaFactura'=>$stringFecha,
					'subTotal'=>$importe[0]['subTotal'],
					'total'=>$importe[0]['total'],
					'folioFiscal'=>$encabezado['folioFiscal'],
					'importePago'=>$importe[0]['total']
				);
					
				//$dbAdapter->insert("Factura", $mFactura);
				//Obtine el ultimo id en tabla factura
				$idFactura = $dbAdapter->lastInsertId("Factura","idFactura");	
				if($formaPago['pagos']!= 0 ){
					print_r("Cantidad como pago en la factura");
				}elseif($formaPago['Pagada']==="1"){
					print_r("Cantidad como pago en la factura");
				}
				//Guarda Movimiento en Cuentasxp
				$mCuentasxp = array(
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idSucursal'=>$encabezado['idSucursal'],
					'idFactura'=>$idFactura,
					'idCoP'=>$encabezado['idCoP'],
					'idBanco'=>$formaPago['idBanco'],
					'idDivisa'=>$formaPago['idDivisa'],
					'numeroFolio'=>$encabezado['numeroFactura'],
					'secuencial'=>1,
					'fechaCaptura'=>date("Y-m-d H:i:s", time()),
					'fechaPago'=>$stringFecha,//Revisar fecha en pagos factura proveedor
					'estatus'=>"A",
					'numeroReferencia'=>$formaPago['numeroReferencia'],
					'conceptoPago'=>$conceptoPago,
					'formaLiquidar'=>$formaPago['formaLiquidar'],
					'subTotal'=>$importe[0]['subTotal'],
					'total'=>$importe[0]['total']
						
				);
				//print_r($mCuentasxp);
				//$dbAdapter->insert("Cuentasxp", $mCuentasxp);
						
				//Obtine el ultimo id en tabla factura
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
	
	public function actualizarSaldoBanco($formaPago){
		
	}
	public function actualizaSaldoCliente($encabezado, $formaPago){
		
	}
	public function guardaDetalleFactura(array $encabezado, $producto, $importe){
		
	}
	
	public function consecutivoEmpresa($encabezado, $idEmpresa){
	
	}
	
	public function guardaImportesImpuesto(array $encabezado, $importe, $producto){
		/*if(! is_null($rowImpuestoProductos)){
			print_r("Select de producto Terminado");
			print_r("$select");
		}else{
			print_r("la consulta esta vacia");
		}*/
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$dbAdapter->beginTransaction();
		
		$tablaImpuestoProductos = $this->tablaImpuestoProductos;
		$select = $tablaImpuestoProductos->select()->from($tablaImpuestoProductos)->where("idProducto=?",$producto['descripcion']);
		$rowImpuestoProductos =$tablaImpuestoProductos->fetchRow($select);
		
		$idImpuesto = $rowImpuestoProductos->idImpuesto;
		print_r("$select");
		$tablaFactura = $this->tablaFactura;
		$select = $tablaFactura->select()->from($tablaFactura,array(new Zend_Db_Expr('max(idFactura) as idFactura')));
		$rowIdFactura =$tablaFactura->fetchRow($select);
		$idFactura = $rowIdFactura['idFactura'];
		
		
		print_r($idImpuesto);
		try{
			if(! is_null($rowImpuestoProductos)){
			$mFacturaImpuesto = array(
				'idFactura'=>$idFactura,
				'idImpuesto'=>$idImpuesto,
				'importe'=>0,
			);
			$dbAdapter->insert("FacturaImpuesto",$mFacturaImpuesto);
			}else{
				print_r("El producto no esta enlazado con impuesto");
			}
			$dbAdapter->commit();		
			}catch(Exception $ex){
				$dbAdapter->rollBack();
				print_r($ex->getMessage());				
				throw new Util_Exception_BussinessException("Error");
		}		
	}
	
	public function guardaIva(array $encabezado, $importe){
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$dbAdapter->beginTransaction();
		
		
		$tablaFactura = $this->tablaFactura;
		$select = $tablaFactura->select()->from($tablaFactura,array(new Zend_Db_Expr('max(idFactura) as idFactura')));
		$rowIdFactura =$tablaFactura->fetchRow($select);
		$idFactura = $rowIdFactura['idFactura'];
		
		try{
			if(!is_null($importe[0]['iva'])){
			$mFacturaImpuesto = array(
				'idFactura'=>$idFactura,
				'idImpuesto'=>15,
				'importe'=>$importe[0]['iva']
			);
			$dbAdapter->insert("FacturaImpuesto",$mFacturaImpuesto);
			}else{
				$mFacturaImpuesto = array(
				'idFactura'=>$idFactura,
				'idImpuesto'=>15,
				'importe'=>0
			);
			$dbAdapter->insert("FacturaImpuesto",$mFacturaImpuesto);	
			}
			$dbAdapter->commit();		
			}catch(Exception $ex){
				$dbAdapter->rollBack();
				print_r($ex->getMessage());				
				throw new Util_Exception_BussinessException("Error");
		}		
	}
		
	
	
}
    
?>
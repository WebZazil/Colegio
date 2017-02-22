<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Contabilidad_DAO_Tesoreria implements Contabilidad_Interfaces_ITesoreria{
	
	private $tablaMovimiento;
	private $tablaFactura;
	private $tablaFacturaDetalle;
	private $tablaImpuesto;
	private $tablaBanco;
	private $tablaEmpresa;
	
	public function __construct(){
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaMovimiento = new Contabilidad_Model_DbTable_Movimientos(array('db'=>$dbAdapter));
		$this->tablaEmpresa    = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));
		$this->tablaImpuesto   = new Contabilidad_Model_DbTable_Impuesto(array('db'=>$dbAdapter));
		$this->tablaBanco      = new Contabilidad_Model_DbTable_Banco(array('db'=>$dbAdapter));
	}
	public function obtenerEmpleadosNomina(){
		
	$tablaEmpresa = $this->tablaEmpresa;
		$select=$tablaEmpresa->select()
		->setIntegrityCheck(false)
		->from($tablaEmpresa, array('idEmpresa'))
		->join('Fiscales', 'Empresa.idFiscales = Fiscales.idFiscales', array('razonSocial'))
		->join('Proveedores','Empresa.idEmpresa = Proveedores.idEmpresa')
		->where('Proveedores.idTipoProveedor = 2')
		->order('razonSocial ASC');
		return $tablaEmpresa->fetchAll($select);		
		
	}
	
	public function sumaBanco($fondeo){
		$tablaBanco = $this->tablaBanco;
		$where = $tablaBanco->getAdapter()->quoteInto("idBanco=?", $fondeo['idBancoE']);
		$rowBanco = $tablaBanco->fetchRow($where);
		print_r($where);
		print_r("<br />");
		$importePago = $rowBanco->saldo;
		print_r($importePago);
		//$tablaBanco->update(array('saldo'=>$importePago),$where);
		
	}
	
	public function restaBanco($nomina){
		$tablaBanco = $this->tablaBanco;
		$where = $tablaBanco->getAdapter()->quoteInto("idBanco", $fondeo['idBancoE']);
		$rowBanco = $tablaBanco->fetchRow($where);
		
		$importePago = $rowBanco->saldo + $fondeo['total'];
		print_r($importePago);
		$tablaBanco->update(array('saldo'=>$importePago),$where);
	}
	
	public function guardaFondeo( array $empresa, $nomina){
		try{
			$mCuentasxc = array(
			'idTipoMovimiento'=>$fondeo['idTipoMovimiento'],
			'idSucursal'=>$fondeo['idSucursal'],
			//'idFactura'=>$datos['idTipoMovimiento'],
			'idCoP'=>$datos['idBancoE'],/**/
			'idBanco'=>$datos['idBancoE'],
			'idDivisa'=>$datos['idDivisa'],
			//'idsImpuestos'=>$datos['idProducto'],
			'numeroFolio'=>$fondeo['numFolio'],
			'secuencial'=>$secuencial,
			'fechaCaptura'=>$fecha,
			'fechaPago'=>$stringfecha,
			'estatus'=>"A",
			'numeroReferencia'=>"",
			'formaLiquidar'=>"LI",
			'conceptoPago'=>$datos["formaPago"],
			'subTotal'=>$datos['total'],
			'total'=>$datos['total']
			);
			$bd->insert("Cuentasxp",$mCuentasxp);
			
			$mCuentasxp = array(
			'idTipoMovimiento'=>$fondeo['idTipoMovimiento'],
			'idSucursal'=>$fondeo['idSucursal'],
			//'idFactura'=>$datos['idTipoMovimiento'],
			'idCoP'=>$datos['idBancoE'],/**/
			'idBanco'=>$datos['idBancoE'],
			'idDivisa'=>$datos['idDivisa'],
			//'idsImpuestos'=>$datos['idProducto'],
			'numeroFolio'=>$fondeo['numFolio'],
			'secuencial'=>$secuencial,
			'fechaCaptura'=>$fecha,
			'fechaPago'=>$stringfecha,
			'estatus'=>"A",
			'numeroReferencia'=>"",
			'conceptoPago'=>$datos["formaPago"],
			'formaLiquidar'=>"LI",
			'subTotal'=>$datos['total'],
			'total'=>$datos['total']
			);
			$bd->insert("Cuentasxp",$mCuentasxp);
			
			$mCuentasxc = array(
			'idTipoMovimiento'=>$fondeo['idTipoMovimiento'],
			'idSucursal'=>$fondeo['idSucursal'],
			//'idFactura'=>$datos['idTipoMovimiento'],
			'idCoP'=>$datos['idBancoS'],/**/
			'idBanco'=>$datos['idBancoS'],
			'idDivisa'=>$datos['idDivisa'],
			//'idsImpuestos'=>$datos['idTipoMovimiento'],
			'numeroFolio'=>$datos[0]['numFolio'],
			'secuencial'=>$secuencial,
			'fechaCaptura'=>$fecha,
			'fechaPago'=>$stringfecha,
			'estatus'=>"A",
			'numeroReferencia'=>"",
			'formaLiquidar'=>$datos['formaPago'],
			'conceptoPago'=>"LI",
			'subtotal'=>$datos['total'],
			'total'=>$datos['total']
			);
			$bd->insert("Cuentasxc",$mCuentasxc);
			
			//=====================================Actualizar Saldo en Bancos
			/*$select=$tablaBancosEmpresa->select()
			->setIntegrityCheck(false)
			->from($tablaBancosEmpresa, array('idBancosEmpresas','idEmpresa'))
			->join('Banco', 'BancosEmpresa.idBanco = Banco.idBanco', array('cuenta'))
			->where('Banco.tipo <>"IN"')
			->order('cuenta ASC');*/
			$tablaBanco = $this->tablaBanco;
			$select = $tablaBanco->select()
			->setIntegrityCheck(false)
			->from($tablaBanco,array('idBanco'))
			->join('BancosEmpresa', 'Banco.idBanco = BancosEmpresa.idBanco',array('idBanco'));
			 
			 /*$tablaBanco = $this->tablaBanco;
			$select = $tablaBanco->select()->from($tablaBanco)->where("idBanco=?",$datos['idBancoE']);
			$row = $tablaBanco->fetchRow($select);*/
			
			/*if (!is_null($row)){
				$saldo = $row->saldo + $datos['total'];
				print_r($saldo);
				$where = $tablaBanco->getAdapter()->quoteInto("idBanco=?",$row->idBanco);
				$tablaBanco->update(array('saldo'=>$saldo), $where);
			}*/
			
			$bd->commit();
		}catch(exception $ex){
			print_r("<br />");
			print_r("==========");
			print_r("Excepcion Lanzada");
			print_r("<br />");
			print_r("==========");
			print_r("<br />");
			print_r($ex->getMessage());
			print_r("<br />");
			print_r("<br />");
			$bd->rollBack();
		}
		
	}
	
	public function guardaNomina( array $empresa, $nomina){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
	
		$dateIni = new  Zend_Date($empresa['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
		try{
			$secuencial=0;	
			/*$tablaMovimiento = $this->tablaMovimiento;
			$select = $tablaMovimiento->select()->from($tablaMovimiento)->where("numeroFolio=?",$empresa['numFolio'])
			->where("idTipoMovimiento =?", $empresa['idTipoMovimiento'])
			->where("idCoP=?",$empresa['idCoP'])
			->where("idSucursal=?",$empresa['idSucursal'])
			->where("fecha=?", $stringIni)
			->order("secuencial DESC");
			$rowMovimiento = $tablaMovimiento->fetchRow($select); */
		
			if(!is_null($rowMovimiento)){
				$secuencial= $rowMovimiento->secuencial +1;
			}else{
				$secuencial = 1;	
			}
			$mMovtos = array(
					'idTipoMovimiento'=>$empresa['idTipoMovimiento'],
					'idEmpresas'=>$empresa['idEmpresas'],
					'idSucursal'=>$empresa['idSucursal'],
					'idCoP'=>$empresa['idCoP'],
					'numeroFolio'=>$empresa['numFolio'],
					//'idFactura'=>0,
					'idProducto' => 199,
					//'idProyecto'=>$encabezado['idProyecto'],
					'cantidad'=>1,
					'fecha'=>$stringIni,
					'secuencial'=> $secuencial,
					'estatus'=>"A",
					'costoUnitario'=>$nomina['sueldo'], //el sueldo del empleado.
					'totalImporte'=>$nomina['nominaxpagar'] //total a pagar de nomina
				);
			//print_r($mMovtos);
			//$dbAdapter->insert("Movimientos",$mMovtos);
			//Guardar en factura.
			$mFactura = array(
				'idTipoMovimiento'=>$empresa['idTipoMovimiento'],
				'idSucursal'=>$empresa['idSucursal'],
				'idCoP'=>$empresa['idCoP'],
				'idDivisa'=>1,
				'numeroFactura'=>$empresa['numFolio'],
				'estatus'=>'A',
				'conceptoPago'=>'L',
				'descuento'=>0,
				'formaPago'=>'EF',
				'fechaFactura'=>$stringIni,
				'subtotal'=>$nomina['sueldo'],
				'total'=>$nomina['nominaxpagar'],
				'folioFiscal'=>0,
				'importePago'=>0
			);
			
			//$dbAdapter->insert("Factura",$mFactura);
			
			$tablaImpuesto = $this->tablaImpuesto;
			$select = $tablaImpuesto->select()->from($tablaImpuesto)->where("idImpuesto = ?", $nomina['36']);
			$rowImpuesto = $tablaImpuesto->fetchRow($select);
			print_r("$select");
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
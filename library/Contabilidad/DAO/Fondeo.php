<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Contabilidad_DAO_Fondeo implements Contabilidad_Interfaces_IFondeo{
	private $tablaBancosEmpresa;
	private $tablaBanco;
	private $tablaCuentasxp;
	private $tablaCuentasxc;
	
	public function __construct(){
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaBancosEmpresa = new Contabilidad_Model_DbTable_BancosEmpresa(array('db'=>$dbAdapter));
		$this->tablaBanco =  new Contabilidad_Model_DbTable_Banco(array('db'=>$dbAdapter));
		$this->tablaCuentasxp = new Contabilidad_Model_DbTable_Cuentasxp(array('db'=>$dbAdapter));
		$this->tablaCuentasxc = new Contabilidad_Model_DbTable_Cuentasxc(array('db'=>$dbAdapter));
	}
		
	public function obtenerBancosEmpresas(){
		$tablaBancosEmpresa = $this->tablaBancosEmpresa;
		$select=$tablaBancosEmpresa->select()
		->setIntegrityCheck(false)
		->from($tablaBancosEmpresa, array('idBancosEmpresas','idEmpresa'))
		->join('Banco', 'BancosEmpresa.idBanco = Banco.idBanco', array('cuenta','banco'))
		->where('Banco.tipo <>"IN"')
		->order('banco ASC');
		return $tablaBancosEmpresa->fetchAll($select);	
	}
	
		public function guardarFondeo(array $datos){
		$fondeo = $datos[0];
		$bd = Zend_Db_Table_Abstract::getDefaultAdapter();
		$bd->beginTransaction();
			
		$fecha =  new Zend_Date($fondeo['fecha'],'YY-MM-dd');
		$stringfecha = $fecha->toString('yyyy-MM-dd');
		$fecha = date('Y-m-d h:i:s', time());
			
		try{
			$secuencial=0;	
			$tablaCuentasxp = $this->tablaCuentasxp;
			$select = $tablaCuentasxp->select()->from($tablaCuentasxp)->where("idTipoMovimiento=?",$datos[0]['idTipoMovimiento'])
			->where("numeroFolio",$datos[0]['numFolio'])
			->where("idSucursal=?",$datos[0]['idSucursal'])
			->where("fechaPago=?", $datos[0]['fecha'])
			->order("secuencial DESC");
		
			$row = $tablaCuentasxp->fetchRow($select); 
			
			if(!is_null($row)){
				$secuencial= $row->secuencial +1;
			}else{
				$secuencial = 1;	
			}
			
			$mMovtos = array(
			'idTipoMovimiento'=>$fondeo['idTipoMovimiento'],
			'idEmpresas'=>$fondeo['idEmpresas'],
			'idSucursal'=>$fondeo['idSucursal'],
			'idCoP'=>$fondeo['idSucursal'],/**/
			'numeroFolio'=>$fondeo['numFolio'],
			//'idFactura'=>$datos['idTipoMovimiento'],
			'idProducto'=>$datos['idProducto'],
			//'idProyecto'=>$datos['idTipoMovimiento'],
			'cantidad'=>1,/**/
			'fecha'=>$stringfecha,
			'secuencial'=>$secuencial,
			'estatus'=>"A",
			'costoUnitario'=>$datos['total'],
			'totalImporte'=>$datos['total'],
			);
			$bd->insert("Movimientos",$mMovtos);
			
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
			'conceptoPago'=>"LI",
			'formaLiquidar'=>$datos["formaPago"],
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
	
}

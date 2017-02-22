<?php
    class Contabilidad_DAO_PagoProveedor implements Contabilidad_Interfaces_IPagoProveedor{
    		
    	private $tablaFactura;
		private $tablaCuentasxp;
		
		public function __construct(){
			$dbAdapter = Zend_Registry::get('dbmodgeneral');
			$this->tablaFactura = new Contabilidad_Model_DbTable_Factura(array('db'=>$dbAdapter));
			$this->tablaCuentasxp = new Contabilidad_Model_DbTable_Cuentasxp(array('db'=>$dbAdapter));
		}
		public function obtieneFacturaProveedor($idSucursal, $idCoP, $numeroFactura){
			$tablaCuentasxp = $this->tablaCuentasxp;
			$select = $tablaCuentasxp->select()->from($tablaCuentasxp)
			->where("idCoP = ?",$idCoP)
			->where ("idSucursal = ?",$idSucursal)
			->where("numeroFolio = ?",$numeroFactura)
			->where("estatus <> ?", "C")
			->where("conceptoPago <> ?","LI")
			->where("idTipoMovimiento =?", 4);
			$rowFacturas = $tablaCuentasxp->fetchAll($select);
		
			print_r("$select");
			/*if (is_null($rowFacturas)){
				return null;
			}else{
				return $rowFacturas->toArray();
			}*/
		}
		
		public function obtenerFactura(){
			
		}
		
		public function obtenerProveedorFactura(){
			$tablaFactura = $this->tablaFactura;
		}
    }
?>
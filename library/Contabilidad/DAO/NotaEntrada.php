<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Contabilidad_DAO_NotaEntrada implements Contabilidad_Interfaces_INotaEntrada{
	
	private $tablaMovimiento;
	private $tablaInventario;
	private $tablaCapas;
	private $tablaMultiplos;
	private $tablaEmpresa;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaMovimiento = new Contabilidad_Model_DbTable_Movimientos(array('db'=>$dbAdapter));
		$this->tablaCapas = new Contabilidad_Model_DbTable_Capas(array('db'=>$dbAdapter));
		$this->tablaInventario = new Contabilidad_Model_DbTable_Inventario(array('db'=>$dbAdapter));
		$this->tablaMultiplos = new Inventario_Model_DbTable_Multiplos(array('db'=>$dbAdapter));
		$this->tablaEmpresa = new Sistema_Model_DbTable_Empresa(array('db'=>$dbAdapter));
		$this->tablaProducto = new Inventario_Model_DbTable_Producto(array('db'=>$dbAdapter));
	}

	public function obtenerProveedores(){
		$tablaEmpresa = $this->tablaEmpresa;
		$select= $tablaEmpresa->select()
		->setIntegrityCheck(false)
		->from($tablaEmpresa, array('idEmpresa'))
		->join('Fiscales', 'Empresa.idFiscales = Fiscales.idFiscales', array('razonSocial'))
		->join('Proveedores','Empresa.idEmpresa = Proveedores.idEmpresa')
		->order('razonSocial ASC');
		return $tablaEmpresa->fetchAll($select);	
	}
	
	
	public function agregarProducto(array $encabezado, $producto){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
	
		$dateIni = new  Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
		
		try{
		
			$secuencial=0;	
			$tablaMovimiento = $this->tablaMovimiento;
			$select = $tablaMovimiento->select()->from($tablaMovimiento)->where("numeroFolio=?",$encabezado['numFolio'])
			->where("idCoP=?",$encabezado['idCoP'])
			->where("idSucursal=?",$encabezado['idSucursal'])
			->where("numeroFolio=?",$encabezado['numFolio'])
			->where("fecha=?", $stringIni)
			->order("secuencial DESC");
	
			$rowMovimiento = $tablaMovimiento->fetchRow($select); 
		
			if(!is_null($rowMovimiento)){
				$secuencial= $rowMovimiento->secuencial +1;
			}else{
				$secuencial = 1;	
			}

			//=================Selecciona producto y unidad=======================================
			$tablaMultiplos = $this->tablaMultiplos;
			$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
			$multiplo = $tablaMultiplos->fetchRow($select); 
			//print_r("$rowMultiplo");
			//====================Operaciones para convertir unidad minima====================================================== 
			$cantidad=0;
			$precioUnitario=0;
			$cantidad = $producto['cantidad'] * $multiplo->cantidad;
			$precioUnitario = $producto['precioUnitario'] / $multiplo->cantidad;
			//print_r($cantidad);
			//print_r($precioUnitario);
				
			$mMovtos = array(
					'idProducto' => $producto['descripcion'],
					'idTipoMovimiento'=>$encabezado['idTipoMovimiento'],
					'idEmpresas'=>$encabezado['idEmpresas'],
					'idSucursal'=>$encabezado['idSucursal'],
					'idCoP'=>$encabezado['idCoP'],
					//'idProyecto'=>$encabezado['idProyecto'],
					'numeroFolio'=>$encabezado['numFolio'],
					//'idFactura'=>0,
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
	
	public function suma(array $encabezado, $producto){
		$dbAdapter =  Zend_Registry::get('dbmodgeneral');	
		$dbAdapter->beginTransaction();
		
		$dateIni = new  Zend_Date($encabezado['fecha'],'YY-MM-dd');
		$stringIni = $dateIni->toString ('yyyy-MM-dd');
			
		try{
		//========================Secuencial==================================================
		$secuencial=0;	
		
		$tablaCapas = $this->tablaCapas;
		$select = $tablaCapas->select()->from($tablaCapas)
		->where("numeroFolio=?",$encabezado['numFolio'])
		->where("fechaEntrada=?", $stringIni)
		->order("secuencial DESC");
	
		$rowCapas = $tablaCapas->fetchRow($select); 
		
		if(!is_null($rowCapas)){
			$secuencial= $rowCapas->secuencial +1;
		}else{
			$secuencial = 1;	
		}
		
		//=================Selecciona producto y unidad=======================================
		$tablaMultiplos = $this->tablaMultiplos;
		$select = $tablaMultiplos->select()->from($tablaMultiplos)->where("idProducto=?",$producto['descripcion'])->where("idUnidad=?",$producto['unidad']);
		$rowMultiplos = $tablaMultiplos->fetchRow($select); 
		//print_r("$select");
		
		//====================Operaciones para convertir unidad minima====================================================== 
			$cantidad=0;
			$precioUnitario=0;
			$cantidad = $producto['cantidad'] * $rowMultiplos->cantidad;
			$precioUnitario = $producto['precioUnitario'] / $rowMultiplos->cantidad;
			
			//print_r($cantidad);
			
		if(!is_null($rowMultiplos)){
			$mCapas = array(
					'idProducto' => $producto['descripcion'],
					'idDivisa'=>$encabezado['idDivisa'],
					'idSucursal'=>$encabezado['idSucursal'],
					'numeroFolio'=>$encabezado['numFolio'],
					'secuencial'=>$secuencial,
					'cantidad'=>$cantidad,
					'fechaEntrada'=>$stringIni,
					'costoUnitario'=>$precioUnitario
			);
			
			$dbAdapter->insert("Capas",$mCapas);
		}
		
		
		$tablaInventario = $this->tablaInventario;
		$select = $tablaInventario->select()->from($tablaInventario)->where("idProducto=?",$producto['descripcion']);
		$rowInventario = $tablaInventario->fetchRow($select);
		
		if(!is_null($rowInventario)){
			$cantidad = $rowInventario->existencia + $cantidad;
			$costoCliente = ($rowInventario->costoUnitario * ($rowInventario->porcentajeGanancia / 100) + $rowInventario->costoUnitario);
			print ("<br />");
			print ($cantidad);
			$where = $tablaInventario->getAdapter()->quoteInto("idProducto = ?", $rowInventario->idProducto);	
			$tablaInventario->update(array('existencia'=> $cantidad,'existenciaReal'=> $cantidad,'existenciaReal'=> $cantidad), $where);	
			//print_r("<br />");
		}else{		
			$mInventario = array(
					'idProducto'=>$producto['descripcion'],
					'idDivisa'=>$encabezado['idDivisa'],
					'idSucursal'=>$encabezado['idSucursal'],
					'existencia'=>$cantidad,
					'apartado'=>'0',
					'existenciaReal'=>$cantidad,
					'maximo'=>'0',
					'minimo'=>'0',
					'fecha'=>$stringIni,
					'costoUnitario'=>$precioUnitario,
					'porcentajeGanancia'=>'0',
					'cantidadGanancia'=>'0',
					'costoCliente'=>($precioUnitario * ($rowInventario->porcentajeGanancia / 100) +$precioUnitario) 
				);
			$dbAdapter->insert("Inventario",$mInventario);
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
}

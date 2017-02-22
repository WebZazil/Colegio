<?php

class Contabilidad_DAO_Proyecto implements Contabilidad_Interfaces_IProyecto {
		
	private $tablaProyecto;
	
	public function __construct()
	{
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$this->tablaProyecto= new Contabilidad_Model_DbTable_Proyecto(array('db'=>$dbAdapter));
	}
	
	public function crearProyecto(Contabilidad_Model_Proyecto $proyecto)
	{
		$bd = Zend_Db_Table_Abstract::getDefaultAdapter();		
		$fechaApertura = new Zend_Date($proyecto->getFechaApertura());	
		$stringIni = $fechaApertura->toString('yyyy-MM-dd hh:mm:ss',time());
	
		$fechaCierre = $proyecto->getFechaCierre();	

		$Ganancia = $proyecto->getCostoFinal()- $proyecto->getCostoInicial();
		
		$proyecto->setFechaApertura($stringIni);
		
		if(($fechaCierre=="")){
			
			$proyecto->setFechaCierre(date("Y-m-d H:i:s", time()));
	
			//print_r("la fecha esta vacia");	
		}else{
			$fechaCierre = new Zend_Date($proyecto->getFechaCierre());	
			$stringIni = $fechaCierre->toString('yyyy-MM-dd');
			$proyecto->setFechaCierre($stringIni);
		}
		$this->tablaProyecto->insert($proyecto->toArray());		
	}
	
	public function obtenerProyectos(){
		$tablaProyecto = $this->tablaProyecto;
		$select = $tablaProyecto->select()->from($tablaProyecto);
		$rowProyectos = $tablaProyecto->fetchAll($select);
		
		$modelProyectos = array();
		
		foreach ($rowProyectos as $rowProyecto) {
			$modelProyecto = new Contabilidad_Model_Proyecto($rowProyecto->toArray());
			$modelProyecto->setIdProyecto($rowProyecto->idProyecto);
			
			$modelProyectos[] = $modelProyecto;
			
		}
		
		return $modelProyectos;
	}
	
	public function obtenerProyecto($idSucursal){
		$tablaProyecto = $this->tablaProyecto;
		$select = $tablaProyecto->select()->from($tablaProyecto)->where("idSucursal=?",$idSucursal);
		$rowsProyectos = $tablaProyecto->fetchAll($select);
		
		if(!is_null($rowsProyectos)){
			return $rowsProyectos->toArray();
		}else{
			return null;
		}
	}
}
<?php

class Sistema_DAO_Subparametro implements Sistema_Interfaces_ISubparametro {
	
	private $tablaSubparametro;
	private $tablaParametro;
	
	function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaSubparametro = new Sistema_Model_DbTable_Subparametro(array('db'=>$dbAdapter));
		$this->tablaParametro = new Sistema_Model_DbTable_Parametro(array('db'=>$dbAdapter));
	}
	
	public function generarClaveProducto(array $claves){
		//print_r($claves);
		$tablaSubparametro= $this->tablaSubparametro;
		$claveProducto = "";
		$idsSubparametro = "";
			
		foreach ($claves as $idParametro => $idSubparametro) {
			if($idSubparametro <> "0"){
				$sub = $this->obtenerSubparametro($idSubparametro);
				$claveProducto .= $sub->getClaveSubparametro();
				$idsSubparametro .=  $sub->getIdSubparametro() . ",";
			}
		}
		
		//print_r($claveProducto);
		//print_r("<br />");
		//print_r($idsSubparametro);
		
		return $claveProducto;
	}
	
	
	public function generarIdsSubparametros(array $claves)
	{
		
		$tablaSubparametro= $this->tablaSubparametro;
		$claveProducto = "";
		$idsSubparametro = "";
		
		foreach ($claves as $idParametro => $idSubparametro) {
			if($idSubparametro <> "0"){
				$sub = $this->obtenerSubparametro($idSubparametro);
				$claveProducto .= $sub->getClaveSubparametro();
				$idsSubparametro .=  $sub->getIdSubparametro() . ",";
			}
		}
		
		return $idsSubparametro;
		
	}
		
	public function obtenerSubparametros($idParametro)
	{
		$tablaSubparametro= $this->tablaSubparametro;
		//$where = $tablaSubparametro->getAdapter()->quoteInto("idParametro = ?", $idparametro);
		$select = $tablaSubparametro->select()->from($tablaSubparametro)->where("idParametro=?",$idParametro)->order("subparametro");
		$rowsSubParametro = $tablaSubparametro->fetchAll($select);
		
		$modelSubParametros = array();
		
		foreach ($rowsSubParametro as $rowSubParametro) {
			$modelSubParametro = new Sistema_Model_Subparametro($rowSubParametro->toArray());
			
			$modelSubParametros[]= $modelSubParametro;
			}
		
		return $modelSubParametros;
		
	}
	
	public function obtenerSubparametro($idSubparametro)
	{
		$tablaSubparametro = $this->tablaSubparametro;
		$select = $tablaSubparametro->select()->from($tablaSubparametro)->where("idSubparametro = ?", $idSubparametro);
		$rowSubparametro = $tablaSubparametro->fetchRow($select);
		
		$subparametroModel = new Sistema_Model_Subparametro($rowSubparametro->toArray());
		$subparametroModel->setIdSubparametro($rowSubparametro->idSubparametro);
		
		return $subparametroModel;
		
	}

	public function crearSubparametro(Sistema_Model_Subparametro $subparametro)
	{
		$tablasubparametro = $this->tablaSubparametro;
		//$select = $tablasubparametro->select()->from($tablasubparametro)->where( "hash = ? ", $subparametro->getHash());
		//$row = $tablasubparametro->fetchRow($select);
		
		//if(!is_null($row)) throw new Util_Exception_BussinessException("Subparámetro: <strong>" . $subparametro->getSubparametro() . "</strong> duplicado en el sistema");
		//$subparametro->setHash($subparametro->getHash());
		$subparametro->setFecha(date("Y-m-d H:i:s", time()));
		
		//$subparametro->setClaveSubparametro($claveSubparametro);
		
		$tablasubparametro->insert($subparametro->toArray());
		
	}
	 
	public function editarSubparametro($idSubparametro, array $subParametro)
	{
		$tablaSubparametro = $this->tablaSubparametro;
		$where = $tablaSubparametro->getAdapter()->quoteInto("idSubparametro = ?", $idSubparametro);
		$tablaSubparametro->update($subParametro, $where);
	}	
	
		
}


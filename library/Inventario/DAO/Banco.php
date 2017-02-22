<?php
/**
 * @author Areli Morales Palma
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
 class Inventario_DAO_Banco implements Inventario_Interfaces_IBanco {
 

	private $tablaBanco;
	
	public function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodgeneral');
		
		$this->tablaBanco = new Contabilidad_Model_DbTable_Banco(array('db'=>$dbAdapter));
	}
	public function obtenerBancos()
	{
		$tablaBanco = $this->tablaBanco;
		$rowBancos = $tablaBanco->fetchAll();
		
		$modelBancos = array();
		foreach ($rowBancos as $rowBanco) {
			$modelBanco = new  Contabilidad_Model_Banco($rowBanco->toArray());
			$modelBanco->setIdBanco($rowBanco->idBanco);
			
			$modelBancos[] = $modelBanco;
		}
		
		return $modelBancos;
	}
	public function obtenerBancosEmpresasFondeo(Contabilidad_Model_Banco $banco)
	{
		$tablaBanco = $this->tablaBanco;
		$select = $tablaBanco->select()->from($tablaBanco)->where('tipo = "IN"');
		$rowBanco = $tablaBanco->fetchRow($select);
		$modelBanco = new Contabilidad_Model_Banco($rowBanco->toArray());
		
		return $modelBanco;
		
	}
	
	public function obtenerBanco($idBanco){
		$tablaBanco = $this->tablaBanco;
		$select = $tablaBanco->select()->from($tablaBanco)->where("idBanco = ?", $idBanco);
		$rowBanco = $tablaBanco->fetchRow($select);
		$modelBanco = new Contabilidad_Model_Banco($rowBanco->toArray());
		
		return $modelBanco;
	}
	
	public function crearBanco(Contabilidad_Model_Banco $banco){
		$tablaBanco = $this->tablaBanco;
		/*$select = $tablaBanco->select()->from($tablaBanco)->where( "hash = ? ", $banco->getHash());
		$row = $tablaBanco->fetchRow($select);
		
		if(!is_null($row)) throw new Util_Exception_BussinessException("Banco: <strong>" . $banco->getBanco() . "</strong> duplicado en el sistema");
		$banco->setHash($banco->getHash());*/
		$banco->setFecha(date("Y-m-d H:i:s", time()));
		
		
		$tablaBanco->insert($banco->toArray());
	}
		
	

	public function editarBanco($idBanco, array $banco){
		$tablaBanco = $this->tablaBanco;
		$where = $tablaBanco->getAdapter()->quoteInto("idBanco= ?", $idBanco);
		$tablaBanco->update($banco, $where);
	}
	
	public function eliminarBanco($idBanco){
		
	}

 }
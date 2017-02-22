<?php
/**
 * 
 */
class Contabilidad_Config_ServiceLocator {
	// Zend Db Adapter personalizado
	private $db;
	
	private static $sl;
	
	public function __construct() {
		
	}
	
	public function getInstance()
	{
		return $this->s;
	}
}

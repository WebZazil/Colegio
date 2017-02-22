<?php
/**
 * 
 */
class Contabilidad_Config_Main {
	// Config
	private $dbConfig = array(
		'host' => '192.168.1.5',
		'username' => 'admin',
		'password' => 'zazil',
		'dbname' => 'GeneralE');
		
	protected $dbAdapter;
	
	public function __construct() {
		$this->dbAdapter = Zend_Db::factory('Pdo_Mysql',$this->dbConfig);
	}
	
	public function getDbAdapter() {
		return $this->dbAdapter;
	}
	
	
	
}

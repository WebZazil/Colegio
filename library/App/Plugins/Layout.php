<?php
/**
 * 
 */
class App_Plugins_Layout extends Zend_Controller_Plugin_Abstract {
	
	private $moduleNames;
	
	/**
	 * @uses $moduleNames as Array containing the names of all the modules in the application.
	 *  
	 */
	function __construct($moduleNames) {
		$this->moduleNames = $moduleNames;
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$nombreModulo = $request->getModuleName();
		$config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$layoutScript = "dash-".$nombreModulo;
		// Seteamos el layout en base al modulo en que nos encontremos.
		if (in_array($nombreModulo, $this->moduleNames)) {
			Zend_Layout::getMvcInstance()->setLayout($layoutScript);
		}
	}
}

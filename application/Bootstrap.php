<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	/**
	 * 
	 */
	protected function _initAclControl() {
    	$config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/acl.ini", "development");
        //$resources = $this->bootstrap('acl')->getResource('acl');
        Zend_Registry::set('acl', $config);
    }
    
	/**
	 * Aqui se registran los namespaces de los modulos complementarios
	 */
	protected function _initAutoloader() {
		$autoloader = Zend_Loader_Autoloader::getInstance();
		// Librerias Externas
		$autoloader->registerNamespace('Zend_');
		$autoloader->registerNamespace('App_');
		$autoloader->registerNamespace('My_');
		$autoloader->registerNamespace('Modules_');
		$autoloader->registerNamespace('Util_');
		// Modulos de la aplicacion
		$autoloader->registerNamespace('Encuesta_');
		$autoloader->registerNamespace('Biblioteca_');
		$autoloader->registerNamespace('Soporte_');
		$autoloader->registerNamespace('Evento_');
	}
	
	/**
	 * Aqui registramos los db adapters y los mandamos al registro de Zend
	 */
	protected function _initDb() {
		$this->bootstrap('multidb');
		$resource = $this->getPluginResource('multidb');

		Zend_Registry::set('multidb', $resource);
		
		Zend_Registry::set('zbase', $resource->getDb('zbase'));
	}
	
	/**
	 * Aqui se inicializa el plugin de seguridad y el de control de acceso de usuarios
	 */
	protected function _initPlugins() {
		// =================================================================  >>>
		$front = Zend_Controller_Front::getInstance();
		
		// Instanciamos el Plugin de Layouts
		$moduleNames = array('encuesta', 'soporte', 'biblioteca', 'evento');
		$front->registerPlugin(new App_Plugins_Layout($moduleNames));
		
		// Instanciamos el plugin ACL
		$recursos = new App_Security_Recurso();
		//$front->registerPlugin(new App_Plugins_Acl($recursos->getAcl()));
		//$front->registerPlugin(new Modules_Controller_Plugin_RequestedModuleLayoutLoader());
		//$front->registerPlugin(new Encuesta_Plugin_Acl($recursos->getAcl()));
	}
	
	protected function _initView() {
		$view = new Zend_View();

		$view->doctype('HTML5');
		$view->headTitle('Colegio Sagrado Corazón')->setSeparator(' :: ');

		return $view;
	}

}

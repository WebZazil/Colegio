<?php
/**
 * 
 */
class Encuesta_Security_CheckAccess extends Zend_Controller_Plugin_Abstract {
	
	private $_auth;
	private $_acl;
	
	protected $_module;
	protected $_controller;
	protected $_action;
	protected $_currentRole;
	protected $_resource;
	
	static private $instance = NULL;
	
	public function __construct() {
		$this->_auth = Zend_Auth::getInstance();
		$this->_acl = new Util_Permissions_Acl(MODULES_PATH . '/encuesta/configs/permissions.ini');
	}
	
	protected function _init(Zend_Controller_Request_Abstract $request) {
		$this->_module = $request->getModuleName();
		$this->_controller = $request->getControllerName();
		$this->_action = $request->getActionName();
		$this->_resource = $this->_module . ':' . $this->_controller;
	}
	
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new Util_Plugin_CheckAccess();
		}
		return self::$instance;
	}
	
	/**
     * Retorna el Rol del usuario actual
     * 
     * @return string
     */
    private function getRol()
    {
    	$rolStr = 'guest';
    	if($this->_auth->hasIdentity()){
    		$numRol = $this->_auth->getIdentity()->rol;
			$tablaRol = new Zend_Db_Table('rol');
			$rol = $tablaRol->fetchRow($tablaRol->select()->from($tablaRol)->where('idRol = ?', $numRol));
			$rolStr = $rol['rol'];
    	}
		print_r($rolStr);
        return $rolStr;
    }
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$controlleName = $request->getControllerName();
		if ($this->_auth->hasIdentity()) {
			
		}else{
			if($controlleName != 'login'){
				$request->setControllerName('index');
				$request->setActionName('login');
			}
		}
	}
	
	/**
     * isAllowed
     * 
     * Retorna si tiene los permisos necesarios para el recurso y el permiso
     * solicitado
     * 
     * @param  string  $resource
     * @param  string  $permission optional
     * @return bool
     */
    public function isAllowed ($resource, $permission = null) {
        // Por defecto, no tiene permisos
        $allow = false;
 
        // Si solo pregunta por el recurso
        if (is_null($permission)) {
            $allow = $this->_acl->isAllowed($this->getRol(), $resource);
        }
        // Si pregunta por el recurso y el permiso
        else {
            $allow = $this->_acl->isAllowed($this->getRol(), $resource, $permission);
        }
 
        return $allow;
    }
}

<?php
/**
 * 
 */
class App_Plugins_Acl extends Zend_Controller_Plugin_Abstract {
	
	protected $_auth;
	protected $_acl;
	protected $_action;
	protected $_controller;
	protected $_module;
	protected $_currentRole;
	
	/**
	 * 
	 */
	function __construct($acl) {
		$this->_auth = Zend_Auth::getInstance();
		$this->_acl = $acl;
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		//$this->_module = $request->getModuleName();
		//$this->_controller = $request->getControllerName();
		//$this->_action = $request->getActionName();
		$this->_init($request);
		//print_r($this->_currentRole."<br />");
        //print_r("<br />");
        //print_r($this->_module."<br />");
		if($this->_module != 'default'){
		    //print_r("Estamos aqui!!");
		    //print_r($this->_module);
            //print_r();
			//Obtenemos el recurso completo: modulo.controller.action
			$recurso = $request->getModuleName()."_".$request->getControllerName()."_".$request->getActionName();
			//print_r("<br />".$recurso."<br />");
			
			// en acl ej: allow.encuesta.home.all
			$recursoAllControllers = $request->getModuleName(); // ej: encuesta
	        $recursoAllActions = $recursoAllControllers."_".$request->getControllerName(); // ej: encuesta_home
	        
			$recursos = $this->_acl->getResources();
            // Rutas por default en caso de no estar autorizado por la estructura ACL
            $defModule = "default";
            $defController = "index";
            $defAction = "index";
            
	        //$module = "default";
            // Controllers de Login y Error
            $loginController = "user";
	        $loginAction = "login";
	        
	        $errorController = "error";
	        $noauthAction = "noauth";
	        $notfoundAction = "notfound";
            // en caso de aun tener $this->_role == "defaultGuest"
            switch ($request->getModuleName()) {
	            case 'encuesta':
                    if($this->_currentRole == "defaultGuest"){
                        $module = $request->getModuleName();
                        $loginController = "home";
                        $loginAction = "index";
                        // ----------------------
                    }
	            	break;
				case 'default':
					
					break;
	        }
            
            //print_r($recursos);
			
			if (in_array($recursoAllControllers, $recursos)) {
	            //print_r("recurso all controllers disponible");
	            if (!$this->_acl->isAllowed($this->_currentRole, $recursoAllControllers, $this->_action)) {
	                // Si el usuario con rol no esta autorizado, enviar a pagina de login para cambiar de rol
	                if('defaultGuest' == $this->_currentRole){
	                    $request->setModuleName($module);
	                    $request->setControllerName($loginController); // controller = home
	                    $request->setActionName($loginAction); // action = login
	                }else{
	                    $request->setModuleName($request->getModuleName());
	                    $request->setControllerName($errorController);
	                    $request->setActionName($noauthAction);
	                }
	            }else{
	                print_r("Permitido para acceder a recurso all controllers<br />");
	            }
	        }elseif(in_array($recursoAllActions, $recursos)){
	            // Tiene acceso a todas las acciones disponibles del recurso (No hay redireccion)
	            // print_r("recurso all actions disponible: <strong>".$recursoAllActions."</strong> <br />");
	            if (!$this->_acl->isAllowed($this->_currentRole, $recursoAllActions, $this->_action)) {
	                //print_r("Usuario con rol: <strong>".$this->_currentRole."</strong> no permitido!!");
	                //print_r("<br />");
	                $request->setModuleName($module);
	                $request->setControllerName($loginController);
	                $request->setActionName($loginAction);
	            }else{
	                //print_r("<br />Usuario con rol: <strong>".$this->_currentRole."</strong> permitido!!<br />");
	                //print_r("<br />");
	                //print_r("Permitido para acceder a recurso all controllers<br />");
	            }
	        }
		}
		
		
	}

	protected function _init(Zend_Controller_Request_Abstract $request) {
        $this->_action = $request->getActionName();
        $this->_controller = $request->getControllerName();
        $this->_module = $request->getModuleName();
        $this->_currentRole = $this->_getCurrentUserRole();
        
    }
	
	protected function _getCurrentUserRole() {
        $role = 'defaultGuest';
        
        if ($this->_auth->hasIdentity()) {
            $authData = $this->_auth->getIdentity();
            //print_r($authData["rol"]);
            $role = isset($authData["rol"]["rol"]) ? $authData["rol"]["rol"] : 'defaultGuest';
        }
        
        return $role;
    }
}

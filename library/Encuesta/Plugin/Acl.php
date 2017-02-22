<?php
/**
 * 
 */
class Encuesta_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
	
    protected $_auth;
    protected $_acl;
    protected $_action;
    protected $_controller;
    protected $_module;
    protected $_currentRole;
    
	function __construct($data) {
		$this->_auth = Zend_Auth::getInstance();
        $this->_acl = $data;
	}
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        	
        $recurso = $request->getModuleName()."_".$request->getControllerName()."_".$request->getActionName();
        
        $recursoAllControllers = $request->getModuleName();
        $recursoAllActions = $recursoAllControllers."_".$request->getControllerName();
        
        //print_r("PluginAcl: function preDispatch");
        //print_r("<br />");
        //print_r($recurso);
        //print_r("<br />");
        //print_r($this->_currentRole);
        //print_r("<br />");
        $recursos = $this->_acl->getResources();
        $module = "default";
        $loginController = "user";
        $loginAction = "login";
        $errorController = "error";
        $noauthAction = "noauth";
        $notfoundAction = "notfound";
        
        switch ($request->getModuleName()) {
            case 'encuesta':
                $module = $request->getModuleName();
                $loginController = "home";
                $loginAction = "login";
            break;
        }
        
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
            }
        }elseif(in_array($recursoAllActions, $recursos)){
            // Tiene acceso a todas las acciones disponibles del recurso (No hay redireccion)
            //print_r("recurso all actions disponible: <strong>".$recursoAllActions."</strong> <br />");
            if (!$this->_acl->isAllowed($this->_currentRole, $recursoAllActions, $this->_action)) {
                //print_r("Usuario con rol: <strong>".$this->_currentRole."</strong> no permitido!!");
                //print_r("<br />");
                $request->setModuleName($module);
                $request->setControllerName($loginController);
                $request->setActionName($loginAction);
            }else{
                //print_r("Usuario con rol: <strong>".$this->_currentRole."</strong> permitido!!");
                //print_r("<br />");
            }
            /*
            if (!$this->_acl->isAllowed($this->_currentRole, $recursoAllActions, $this->_action)) {
                switch ($request->getModuleName()) {
                    case 'encuesta':
                        $newModule = $request->getModuleName();
                        $newController = "home";
                        $newAction = "index";
                        break;
                }
                if('defaultGuest' == $this->_currentRole){
                    $request->setModuleName($request->getModuleName());
                    $request->setControllerName($newController);
                    $request->setActionName($newAction);
                }else{
                    $request->setControllerName("error");
                    $request->setActionName("noauth");
                }
            }*/
        }
        
        //$this->_acl->isAllowed($rol, $controller, $action);
        //$this->_acl->isAllowed($rol, $recurso, $privilegio);
        /*
        if (!$this->_acl->isAllowed($this->_currentRole, $this->_controller, $this->_action)) {
            if ('guest' == $this->_currentRole) {
                $request->setControllerName("user");
                $request->setActionName("login");
            }else{
                $request->setControllerName("error");
                $request->setActionName("noauth");
            }
        }
        */
        
        //print_r("NuevaRuta: Modulo-Controller-Action: ". $request->getModuleName() . " - " .  $request->getControllerName() . " - " . $request->getActionName(). "<br />");
        //print_r("<br />");
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

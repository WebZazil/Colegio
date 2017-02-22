<?php
/**
 * 
 */
class Encuesta_Security_Acl extends Zend_Acl {
	
	function __construct() {
		$aclConfig = Zend_Registry::get('acl');
        //print_r($aclConfig);
        //print_r("<br /><br />");
        
        $roles = $aclConfig->acl->roles;
        $resources = $aclConfig->acl->resources;
        $this->_addRoles($roles);
        $this->_addResources($resources);
	}
    
    /**
     * Crea los roles de nuestra estructura ACL
     */
    protected function _addRoles($roles) {
        
        foreach ($roles as $modulo) {
            foreach ($modulo as $user => $rol) {
                //print_r($modulo);
                //print_r("-" . $user.".".$rol."<br />");
                //print_r("-" . $user."<br />");
                //print_r("-" . $rol);
                if (!$this->hasRole($user)) {
                    // Verificamos los roles padre del rol
                    if (empty($rol)) {
                        $rol = null;
                    } else {
                        $rol = explode(",", $rol);
                    }
                    $this->addRole(new Zend_Acl_Role($user), $rol);
                }
            }
        }
        // Iteramos a travez de los roles
        /*
        foreach ($roles as $name => $parents) {
            // Verificamos que no este el rol ya dado de alta
            if (!$this->hasRole($name)) {
                // Verificamos los roles padre del rol
                if (empty($parents)) {
                    $parents = null;
                } else {
                    $parents = explode(",", $parents);
                }
                $this->addRole(new Zend_Acl_Role($name), $parents);
            }
        }*/
        
        
    }
    
    /**
     * Agrega los recursos al ACL, y asocia los permisos de acceso (allow, deny)
     */
    /*
    protected function _addResources($resources) {
        
        foreach ($resources as $permissions => $controllers) {
            
            foreach ($controllers as $controller => $actions) {
                
                if ($controller == 'all') {
                    $controller = null;
                } else {
                    if (!$this->has($controller)) {
                        $this->add(new Zend_Acl_Resource($controller));
                    }
                }
                
                foreach ($actions as $action => $role) {
                    
                    if ($action == 'all') {
                        $action = null;
                    }
                    
                    if ($permissions == 'allow') {
                        $this->allow($role,$controller,$action);
                    }
                    
                    if ($permissions == 'deny') {
                        $this->deny($role, $controller, $action);
                    }
                    
                }
                
            }
        }
    }*/

    protected function _addResources($resources) {
        
        foreach ($resources as $permissions => $modules) {
            // $permissions = allow | deny : $modules = Zend_Config_Object("todos los modulos")
            //print_r("permiso: ".$permissions."<br />");
            //print_r("==================================<br />");
            foreach ($modules as $nameModule => $controllers) {
                // $controller = nombreModulo : $actions = Zend_Config_Object("todos los controllers de los modulos")
                //print_r("NombreModulo: ".$nameModule);
                //print_r("<br />");
                foreach ($controllers as $nameController => $actions) {
                    //print_r("NombreController: ".$nameController);
                    //print_r("<br />");
                    foreach ($actions as $nameAction => $user) {
                        $action = null;
                        //print_r("NombreAction: ".$nameAction);
                        //print_r("<br />");
                        //print_r("Usuario: ".$user);
                        //print_r("<br />");
                        $recurso = $nameModule;
                        //print_r($recurso);
                        //print_r("<br />");
                        // Si nameController = all aplica a todos los controllers del modulo
                        if ($nameController == "all") {
                            //print_r("Nombre Controller NULL !!<br />");
                            // Si nameAction = all aplica atodas las acciones del controller
                            if ($nameAction == "all") {
                                
                            }elseif($nameAction == "none"){
                                
                            }
                        }else{
                            $recurso = $recurso."_".$nameController;
                            // Si nameAction = all aplica atodas las acciones del controller
                            if ($nameAction == "all") {
                                
                            }else{
                                $recurso = $recurso."_".$nameAction;
                            }
                        }
                        
                        //$numero = count(explode("_", $recurso));
                        //print_r("Recursos: ". $recurso." Elementos: ".$numero);
                        if (!$this->has($recurso)) {
                            $this->add(new Zend_Acl_Resource($recurso));
                        }
                        
                        if ($nameAction != 'all') {
                            $action = $nameAction;
                        }
                        
                        if ($permissions == 'allow') {
                            $this->allow($user,$recurso);
                        }
                        
                        if ($permissions == 'deny') {
                            $this->deny($user, $recurso);
                        }
                        
                        // Si nameController == all la regla se aplica a todos los controllers
                        /*
                        if ($nameController == 'all') {
                            $resource = null;
                        } else {
                            //$this->has($resource);
                            if (!$this->has($controller)) {
                                $this->add(new Zend_Acl_Resource($controller));
                            }
                        }*/
                        
                        //print_r("<br /><br />");
                    }
                }
            }
            
            /*
            foreach ($controllers as $controller => $actions) {
                
                if ($controller == 'all') {
                    $controller = null;
                } else {
                    if (!$this->has($controller)) {
                        $this->add(new Zend_Acl_Resource($controller));
                    }
                }
                
                foreach ($actions as $action => $role) {
                    
                    if ($action == 'all') {
                        $action = null;
                    }
                    
                    if ($permissions == 'allow') {
                        $this->allow($role,$controller,$action);
                    }
                    
                    if ($permissions == 'deny') {
                        $this->deny($role, $controller, $action);
                    }
                    
                }
                
            }*/
            
        }
        //print_r("function addResources <br />");
    }


}

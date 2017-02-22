<?php
/**
 * 
 */
class App_Security_Recurso {
	
	function __construct() {
		
	}
	
	public function getAcl() {
		$config = Zend_Registry::get('acl');
		$roles = $config->acl->roles;
		$recursos = $config->acl->resources;
		
		$acl = new Zend_Acl;
		
		foreach ($roles as $modulo) {
            foreach ($modulo as $user => $rol) {
                //print_r($modulo);
                //print_r("-" . $user.".".$rol."<br />");
                //print_r("-" . $user."<br />");
                //print_r("-" . $rol);
                if (!$acl->hasRole($user)) {
                    // Verificamos los roles padre del rol
                    if (empty($rol)) {
                        $rol = null;
                    } else {
                        $rol = explode(",", $rol);
                    }
                    $acl->addRole(new Zend_Acl_Role($user), $rol);
                }
            }
        }
		
		foreach ($recursos as $permissions => $modules) {
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
                        if (!$acl->has($recurso)) {
                            $acl->add(new Zend_Acl_Resource($recurso));
                        }
                        
                        if ($nameAction != 'all') {
                            $action = $nameAction;
                        }
                        
                        if ($permissions == 'allow') {
                            $acl->allow($user,$recurso);
                        }
                        
                        if ($permissions == 'deny') {
                            $acl->deny($user, $recurso);
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
            }
		
		//$acl->addResource($resource);
		return $acl;
	}
}

<?php
/**
 * Encuesta_Util_Service :: Clase de Servicio
 */
class Encuesta_Util_Service {
	
    private $loginDAO = null;
    
	function __construct() {
		$this->loginDAO = new Encuesta_DAO_Login;
	}
    
    public function getNamespace($datos) {
        $organizacion = $this->loginDAO->getOrganizacionByClave($datos["claveOrganizacion"]);
        $usuario = $this->loginDAO->getUsuario($datos);
        
        $namespace = str_replace(" ", "", $organizacion["nombre"]);
        $namespace = str_replace(".", "", $namespace);
        $namespace = strtolower($namespace);
        $user = str_replace(" ", "", $usuario["nickname"]);
        $user = strtolower($user);
        
        $namespace = $namespace."_".$user;
        
        return $namespace;
        //print_r($namespace);
    }
    
    public function login(array $params) {
        $organizacion = $this->loginDAO->esOrganizacion($params['claveOrganizacion']);
        $usuario = null;
        if(!is_null($organizacion)){
            $usuario = $this->loginDAO->existeUsuarioOrganizacion($params['usuario'], $params['password'], $organizacion['id']);
            if(!is_null($usuario)){
                print_r($usuario);
            }else{
                print_r("Usuario nulo");
            }
        }else{
            print_r("Organizacion nula");
        }
        //Eliminar espacios en blanco del nombre de la organizacion
        $namespace = str_replace(" ", "", $organizacion["nombre"]);
        //Eliminar puntos del nombre de la organizacion
        $namespace = str_replace(".", "", $namespace);
        //Transformar a minusculas todos los caracteres del nombre de la organizacion
        $namespace = strtolower($namespace);
        print_r("<br />");
        print_r($namespace);
        
        $user = str_replace(" ", "", $usuario["nickname"]);
        $user = strtolower($user);
        print_r("<br />");
        print_r($user);
        
        $namespace = $namespace."_".$user;
        print_r("<br />");
        print_r($namespace);
        // Con este namespace iniciamos session de simple instancia, es decir, no se puede hacer login 2 veces
        $session = new Zend_Session_Namespace($namespace,TRUE);
        print_r("<br />");
        print_r($session->getNamespace());
        return $session;
    }
}

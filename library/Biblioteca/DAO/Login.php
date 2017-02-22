<?php
/**
 * 
 */
class Biblioteca_DAO_Login {
	
    private $tableOrganizacion;
    private $tableUsuario;
    private $tableSubscripcion;
    private $tableRol;
    
	function __construct() {
		$dbBaseAdapter = Zend_Registry::get("dbbasebiblio");
        
        $this->tableOrganizacion = new Biblioteca_Model_DbTable_Organizacion(array('db'=>$dbBaseAdapter));
        $this->tableRol = new Biblioteca_Model_DbTable_Rol(array('db'=>$dbBaseAdapter));
        $this->tableUsuario = new Biblioteca_Model_DbTable_Usuario(array('db'=>$dbBaseAdapter));
        $this->tableSubscripcion = new Biblioteca_Model_DbTable_Subscripcion(array('db'=>$dbBaseAdapter));
	}
    
    /**
     * 
     */
    public function getSubscripcionByIdorganizacion($idOrganizacion) {
        $tableSubscription = $this->tableSubscripcion;
        $where = $tableSubscription->getAdapter()->quoteInto("idOrganizacion=?", $idOrganizacion);
        $rowSubscripcion = $tableSubscription->fetchRow($where);
        if (!is_null($rowSubscripcion)) return $rowSubscripcion->toArray();
        else return null;
    }
    
    /**
     * 
     */
    public function getRolbyId($idRol) {
       $tablaRol = $this->tableRol;
       $where = $tablaRol->getAdapter()->quoteInto("idRol=?", $idRol);
       $rowRol = $tablaRol->fetchRow($where);
       if (!is_null($rowRol)) return $rowRol->toArray();
        else return null; 
    }
    
    /**
     * 
     */
    public function getOrganizacionByClaveOrganizacion($claveOrganizacion) {
        $tablaOrg = $this->tableOrganizacion;
        $select = $tablaOrg->select()->from($tablaOrg)->where("claveOrganizacion=?",$claveOrganizacion);
        $rowOrganizacion = $tablaOrg->fetchRow($select);
        
        if (is_null($rowOrganizacion)) {
            return null;
        } else {
            return $rowOrganizacion->toArray();
        }
        
    }
    
    public function loginByClaveorganizacion($claveOrganizacion) {
        if (!is_null($claveOrganizacion)) {
            $organizacion = $this->getOrganizacionByClaveOrganizacion($claveOrganizacion);
            // Creamos un Adapter para loguearnos con un usuario por defecto con el rol necesario para ejecutar consultas
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbbasebiblio'),"Usuario","nickname","password",'SHA1(?)');
            $authAdapter->setIdentity("test")->setCredential("zazil");
            
            $auth = Zend_Auth::getInstance();
            $resultado = $auth->authenticate($authAdapter);
            
            if($resultado->isValid()){
                //print_r("<br />Autentificado con clave <br />");
                $data = $authAdapter->getResultRowObject(null,'password');
                $subscripcion = $this->getSubscripcionByIdorganizacion($organizacion["idOrganizacion"]);
                $n_adapter = $subscripcion["adapter"];
                $currentDbConnection = array();
                $currentDbConnection["host"] = $subscripcion["host"];
                $currentDbConnection["username"] = $subscripcion["username"];
                $currentDbConnection["password"] = $subscripcion["password"];
                $currentDbConnection["dbname"] = $subscripcion["dbname"];
                
                $db = Zend_Db::factory(strtoupper($n_adapter), $currentDbConnection);
                
                $userInfo = array();
                $userInfo["user"] = $data;
                $userInfo["rol"] = $this->getRolbyId($data->idRol);
                $userInfo["organizacion"] = $organizacion;
                $userInfo["adapter"] = $db;
                $auth->getStorage()->clear();
                $auth->getStorage()->write($userInfo);
                
            }
            
        }
    }
}

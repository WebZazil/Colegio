<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Soporte_DAO_Login {
    
    private $tableOrganizacion;
    private $tableUsuario;
    private $tableSubscripcion;
    private $tableRol;
	
	function __construct() {
		//$dbBaseAdapter = Zend_Registry::get("zbase");
		
		$config = array('db'=> Zend_Registry::get("zbase"));
        
        $this->tableOrganizacion = new Soporte_Models_DbTable_Organizacion($config);
        $this->tableRol = new Soporte_Models_DbTable_Rol($config);
        $this->tableUsuario = new Soporte_Models_DbTable_Usuario($config);
        $this->tableSubscripcion = new Soporte_Models_DbTable_Subscripcion($config);
        
	}
    
    /**
     * 
     * @param Integer $idOrganizacion
     * @return array|NULL
     */
    public function getSubscripcionByIdOrganizacion($idOrganizacion) {
        $tableSubscription = $this->tableSubscripcion;
        $where = $tableSubscription->getAdapter()->quoteInto("idOrganizacion=?", $idOrganizacion);
        $rowSubscripcion = $tableSubscription->fetchRow($where);
        if (!is_null($rowSubscripcion)) return $rowSubscripcion->toArray();
        else return null;
    }
    
    /**
     * 
     * @param unknown $idOrganizacion
     * @return array
     */
    public function getQuerySubscripcionByOrganizacion($idOrganizacion) {
        $tSubs = $this->tableSubscripcion;
        $select = $tSubs->select()->from($tSubs)->where("idModulo=?", MOD_SOPORTE)
            ->where("idOrganizacion=?",$idOrganizacion)
            ->where("tipo=?","Q");
        
        $rowSubs = $tSubs->fetchRow($select);
        return $rowSubs->toArray();
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
    
    /**
     * 
     * @param String $claveOrganizacion
     */
    public function loginByClaveOrganizacion($claveOrganizacion) {
        if (!is_null($claveOrganizacion)) {
            $organizacion = $this->getOrganizacionByClaveOrganizacion($claveOrganizacion);
            // Creamos un Adapter para loguearnos con un usuario por defecto con el rol necesario para ejecutar consultas
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('zbase'),"Usuario","nickname","password",'SHA1(?)');
            $authAdapter->setIdentity("test")->setCredential("zazil");
            
            $auth = Zend_Auth::getInstance();
            $resultado = $auth->authenticate($authAdapter);
            
            if($resultado->isValid()) {
                //print_r("<br />Autentificado con clave <br />");
                $data = $authAdapter->getResultRowObject(null,'password');
                $subscripcion = $this->getSubscripcionByIdOrganizacion($organizacion["idOrganizacion"]);
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

<?php
/**
 * Class Login
 * @author Hector Rodriguez
 * @copyright Zazil Consultores S.A. de C.V.
 */
class Encuesta_DAO_Login {

    private $tableOrganizacion;
    private $tableUsuario;
    private $tableSubscripcion;
    private $tableRol;

	function __construct() {
	    //$dbBaseAdapter = Zend_Registry::get("dbbaseencuesta");
	    $dbBaseAdapter = Zend_Registry::get("dbmodadmin");
        
		$this->tableOrganizacion = new Encuesta_Model_DbTable_Organizacion(array('db'=>$dbBaseAdapter));
        $this->tableSubscripcion = new Encuesta_Model_DbTable_Subscripcion(array('db'=>$dbBaseAdapter));
        $this->tableRol = new Encuesta_Model_DbTable_Rol(array('db'=>$dbBaseAdapter));
        $this->tableUsuario = new Encuesta_Model_DbTable_Usuario(array('db'=>$dbBaseAdapter));
	}
    
    /**
     * 
     */
    public function getOrganizacionByClave($claveOrganizacion) {
       $tableOrganizacion = $this->tableOrganizacion;
       $where = $tableOrganizacion->getAdapter()->quoteInto("claveOrganizacion=?", $claveOrganizacion);
       $rowOrganizacion = $tableOrganizacion->fetchRow($where);
       
       if(!is_null($rowOrganizacion)) return $rowOrganizacion->toArray();
       else return null; 
    }
    
    /**
     * 
     */
    public function getSubscripcion($idOrganizacion) {
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
     * Accedemos al sistema solo con la clave de la organizacion
     * Autentificamos con el usuario: encuestaTest - usuario con privilegios de consulta.
     */
    public function loginByClaveOrganizacion($claveOrganizacion) {
        if(!is_null($claveOrganizacion)){
            $organizacion = $this->getOrganizacionByClave($claveOrganizacion);
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbmodadmin'),"Usuario","nickname","password",'SHA1(?)');
            $authAdapter->setIdentity("test")->setCredential("zazil");
            
            $auth = Zend_Auth::getInstance();
            $resultado = $auth->authenticate($authAdapter);
            
            if($resultado->isValid()){
                //print_r("<br />Autentificado con clave <br />");
                $data = $authAdapter->getResultRowObject(null,'password');
                $subscripcion = $this->getSubscripcion($organizacion["idOrganizacion"]);
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
                
            }else{
                
            }
            
        }
    }
    
    
}

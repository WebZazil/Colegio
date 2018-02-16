<?php
/**
 *
 * @author EnginnerRodriguez
 *
 */
class App_Data_DAO_Login {
    
    private $tOrganizacion;
    private $tSubscripcion;
    private $tRol;
    private $tUsuario;
    private $tModulo;
    
    public function __construct() {
        // $dbAdapter = Zend_Registry::get("zbase");
        $config = array('db' => Zend_Registry::get("zbase"));
        
        $this->tOrganizacion = new App_Data_DbTable_Organizacion($config);
        $this->tSubscripcion = new App_Data_DbTable_Subscripcion($config);
        $this->tRol = new App_Data_DbTable_Rol($config);
        $this->tUsuario = new App_Data_DbTable_Usuario($config);
        $this->tModulo = new App_Data_DbTable_Modulo($config);
    }
    
    /**
     *
     * @param String $claveOrganizacion
     */
    public function getOrganizacionByClave($claveOrganizacion) {
        $tOrg = $this->tOrganizacion;
        $select = $tOrg->select()->from($tOrg)->where('claveOrganizacion=?',$claveOrganizacion);
        
        $rowOrganizacion = $tOrg->fetchRow($select);
        
        return $rowOrganizacion->toArray();
    }
    
    private function getRolById($idRol) {
        $tRol = $this->tRol;
        $select = $tRol->select()->from($tRol)->where('idRol=?',$idRol);
        
        $rowRol = $tRol->fetchRow($select);
        return $rowRol->toArray();
    }
    
    /**
     *
     * @param array $params
     */
    private function getUsuarioByParams($params) {
        $tUs = $this->tUsuario;
        $select = $tUs->select()->from($tUs);
        foreach ($params as $k => $v) {
            $select->where($k.'=?',$v);
        }
        //print_r($select->__toString());
        $rowUsuario = $tUs->fetchRow($select);
        return $rowUsuario->toArray();
    }
    
    /**
     *
     * @param string $tipo
     * @return array
     */
    private function getModuloByTipo($tipo) {
        $tM = $this->tModulo;
        $select = $tM->select()->from($tM)->where('clave=?',$tipo);
        $rowModulo = $tM->fetchRow($select);
        
        return $rowModulo->toArray();
    }
    
    /**
     * 
     * @param array $credentials debe tener las claves:
     * nickname => val
     * password => val
     * claveOrganizacion => val
     * @throws Exception
     */
    public function login($credentials) {
        try {
            if ( (isset($credentials['nickname']) && $credentials['nickname'] != '' ) && 
                    (isset($credentials['password']) && $credentials['password'] != '' ) && 
                        (isset($credentials['clave']) && $credentials['clave'] != '' ) ) {
                
                $tOrg = $this->tOrganizacion;
                $select = $tOrg->select()->from($tOrg)->where('claveOrganizacion=?', $credentials['clave']);
                $rowOrg = $tOrg->fetchRow($select);
                
                if (!is_null($rowOrg)) {
                    $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('zbase'), 'Usuario', 'nickname', 'password', 'SHA1(?)');
                    $authAdapter->setIdentity($credentials['nickname'])->setCredential($credentials['password']);
                    
                    $auth = Zend_Auth::getInstance();
                    $resultado = $auth->authenticate($authAdapter);
                    
                    if ($resultado->isValid()) {
                        $data = $authAdapter->getResultRowObject(null,'password');
                        
                        $tRol = $this->tRol;
                        $select = $tRol->select()->from($tRol)->where('idRol=?', $data->idRol);
                        $rowRol = $tRol->fetchRow($select);
                        
                        if ($rowRol['rol'] != 'system') {
                            $auth->clearIdentity();
                            throw new Exception('Usuario no tiene permisos suficientes');
                        }else{
                            $tSub = $this->tSubscripcion;
                            $select = $tSub->select()->from($tSub)->where('idOrganizacion=?', $rowOrg->idOrganizacion)
                                ->where('idRol=?', $data->idRol);
                            $rowSub = $tSub->fetchRow($select)->toArray();
                            //print_r($rowSub->toArray());
                            
                            $conn = array();
                            $conn['host'] = $rowSub['host'];
                            $conn['dbname'] = $rowSub['dbname'];
                            $conn['username'] = $rowSub['username'];
                            $conn['password'] = $rowSub['password'];
                            $conn['charset'] = $rowSub['charset'];
                            
                            $newDb = Zend_Db::factory(strtoupper($rowSub['adapter']), $conn );
                            
                            $sessionObj = array();
                            $sessionObj['org'] = $rowOrg->toArray();
                            $sessionObj['adapter'] = $newDb;
                            
                            $auth->getStorage()->clear();
                            $auth->getStorage()->write($sessionObj);
                        }
                    }else{
                        throw new Exception('Usuario y/o password incorrectos');
                    }
                }else{
                    throw new Exception('Clave no encontrada');
                }
            }else{
                throw new Exception('Credenciales Incompletas');
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            throw new Exception($message);
        }
        
    }
    
    /**
     * Usado para el caso del Colegio Sagrado Corazon.
     * @param array $credentials
     */
    public function simpleLogin($credentials, $claveOrg = 'colsagcor16', $tipoModulo = 'MOD_ENCUESTA') {
        if ( (isset($credentials['nickname']) && $credentials['nickname'] != '' ) &&  (isset($credentials['password']) && $credentials['password'] != '' ) ) {
            //print_r('On simpleLogin<br /><br />');
            $organizacion = $this->getOrganizacionByClave($claveOrg);
            $modulo = $this->getModuloByTipo($tipoModulo);
            
            $auth = Zend_Auth::getInstance();
            
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('zbase'), 'Usuario','nickname','password','SHA1(?)');
            $authAdapter->setIdentity($credentials['nickname'])->setCredential($credentials['password']);
            
            $resultado = $auth->authenticate($authAdapter);
            
            if ($resultado->isValid()) {
                //print_r('Resultado Valido: <br /><br />');
                $datos = $authAdapter->getResultRowObject(null,null);
                $rol = $this->getRolById($datos->idRol);
                print_r($rol);
                $tSub = $this->tSubscripcion;
                $select = $tSub->select()->from($tSub)
                    ->where('idOrganizacion=?',$organizacion['idOrganizacion'])
                    ->where('idModulo=?',$modulo['idModulo'])
                    ->where('idRol=?',$rol['idRol']);
                
                $rowSub = $tSub->fetchRow($select)->toArray();
                
                $conn = array();
                $conn['host'] = $rowSub['host'];
                $conn['username'] = $rowSub['username'];
                $conn['password'] = $rowSub['password'];
                $conn['dbname'] = $rowSub['dbname'];
                $conn['charset'] = $rowSub['charset'];
                
                $db = Zend_Db::factory(strtoupper($rowSub['adapter']), $conn);
                
                $sessionObj = array();
                $sessionObj['adapter'] = $db;
                $sessionObj['user'] = $datos;
                $sessionObj['rol'] = $rol;
                $sessionObj['organizacion'] = $organizacion;
                
                $auth->getStorage()->write($sessionObj);
                
            }else{
                $messages = $resultado->getMessages();
                print_r($resultado->getCode());
                $strMessage = '<ul>';
                foreach ($messages as $message){
                    $strMessage .= '<li>'.$message.'</li>';
                }
                $strMessage .= '</ul>';
                throw new Exception('Error de authenticacion: <strong>'.$strMessage.'</strong>');
            }
        } else {
            throw new Exception('Error en credenciales');
        }
    }
    
    /**
     * 
     * @param array $credentials
     * @param string $claveOrg
     * @param string $tipoModulo
     * @return Zend_Db_Adapter_Abstract
     */
    public function getTestConnector($credentials, $claveOrg = 'colsagcor16', $tipoModulo = 'MOD_ENCUESTA') {
        $tSub = $this->tSubscripcion;
        $organizacion = $this->getOrganizacionByClave($claveOrg);
        //$auth = Zend_Auth::getInstance();
        
        $usuario = $this->getUsuarioByParams($credentials);
        $rol = $this->getRolById($usuario['idRol']);
        $modulo = $this->getModuloByTipo($tipoModulo);
        
        $select = $tSub->select()->from($tSub)
             ->where('idOrganizacion=?',$organizacion['idOrganizacion'])
             ->where('idModulo=?',$modulo['idModulo'])
             ->where('idRol=?',$rol['idRol']);
       
        $rowSub = $tSub->fetchRow($select)->toArray();
        
        $connector = array();
        $connector['host'] = $rowSub['host'];
        $connector['username'] = $rowSub['username'];
        $connector['password'] = $rowSub['password'];
        $connector['dbname'] = $rowSub['dbname'];
        $connector['charset'] = $rowSub['charset'];
        
        $db = Zend_Db::factory(strtoupper($rowSub['adapter']), $connector);
        
        return $db;
    }
    
    /**
     * 
     * @param array $credentials
     * @param string $claveOrg
     * @param string $tipoModulo
     * @return Zend_Db_Adapter_Abstract
     */
    public function getSystemConnector(array $credentials, $claveOrg = 'colsagcor16', $tipoModulo = 'MOD_ENCUESTA') {
        $organizacion = $this->getOrganizacionByClave($claveOrg);;
        $usuario = $this->getUsuarioByParams($credentials);
        $rol = $this->getRolById($usuario['idRol']);
        $modulo = $this->getModuloByTipo($tipoModulo);
        
        $tSub = $this->tSubscripcion;
        $select = $tSub->select()->from($tSub)
            ->where('idOrganizacion=?',$organizacion['idOrganizacion'])
            ->where('idModulo=?',$modulo['idModulo'])
            ->where('idRol=?',$rol['idRol']);
        $rowSub = $tSub->fetchRow($select)->toArray();
        
        $connector = array();
        $connector['host'] = $rowSub['host'];
        $connector['username'] = $rowSub['username'];
        $connector['password'] = $rowSub['password'];
        $connector['dbname'] = $rowSub['dbname'];
        $connector['charset'] = $rowSub['charset'];
        
        $db = Zend_Db::factory(strtoupper($rowSub['adapter']), $connector);
        
        return $db;
    }
    
}
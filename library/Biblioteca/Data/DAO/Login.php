<?php
class Biblioteca_Data_DAO_Login {
    
    private $appDAO;
    private $dbConnection;
    private $tableUsuario;
    private $tableAdmin;
    
    public function __construct($dbAdapter) {
        $this->dbConnection = $dbAdapter;
        $this->appDAO = new App_Data_DAO_Login();
        
        $config = array('db' => $dbAdapter);
        
        $this->tableUsuario = new Biblioteca_Data_DbTable_Usuario($config);
        $this->tableAdmin = new Biblioteca_Data_DbTable_Admin($config);
    }
    
    public function login($data){
        
    }
    
    /**
     * 
     * @param array $params
     */
    public function loginUser($params) {
        $auth = Zend_Auth::getInstance();
        //$auth->clearIdentity();
        
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->dbConnection,'Usuario','nickname','password',null);
        $authAdapter->setIdentity($params['nickname'])->setCredential($params['password']);
        $resultado = $auth->authenticate($authAdapter);
        
        if ($resultado->isValid()) {
            print_r($resultado->getMessages());
            $datos = $authAdapter->getResultRowObject(null,null);
            
            $sessionObj = array(
                'adapter' => $this->dbConnection,
                'user' => get_object_vars($datos),
            );
            
            $auth->getStorage()->write($sessionObj);
        }else {
            print_r($resultado->getMessages());
            $mensajes = $resultado->getMessages();
            $strMsg = "";
            foreach ($mensajes as $mensaje){
                switch ($mensaje) {
                    case 'Supplied credential is invalid.' :
                        $mensaje = 'Password incorrecto';
                        break;
                    case 'A record with the supplied identity could not be found.':
                        $mensaje = 'Nickname no registrado en sistema';
                        break;
                }
                $strMsg .= $mensaje.'<br />';
            }
            
            throw new Exception($strMsg);
        }
    }
    
    public function loginSystem($params) {
        $tA = $this->tableAdmin;
        $select = $tA->select()->from($tA)
            ->where('nickname=?',$params['nickname'])
            ->where('password=?',$params['password']);
        
        $rowAdmin = $tA->fetchRow($select);
        
        return $rowAdmin->toArray();
    }
}
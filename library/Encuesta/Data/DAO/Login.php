<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Login {
    
    private $tableUsuario;
    private $tableRol;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
    }
    
    public function login($data) {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('zbase'), 'Usuario','nickname','password', 'SHA1(?)');
        $authAdapter->setIdentity($data['usuario'])->setCredential($data['password']);
        
        $resultado = $authAdapter->authenticate();
        $messages = $resultado->getMessages();
        foreach ($messages as $message){
            print_r($message);
        }
    }
}

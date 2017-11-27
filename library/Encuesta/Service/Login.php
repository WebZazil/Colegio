<?php
class Encuesta_Service_Login {
    
    private $appLoginDAO;
    
    public function __construct() {
        // AppLogin no necesita constructor
        $this->appLoginDAO = new App_Data_DAO_Login();
    }
    
    public function login($data) {
    }
    
    public function simpleLogin($data, $claveOrganizacion, $tipoModulo)  {
        //print_r('class:Encuesta_Service_Login <br />');
        $this->appLoginDAO->simpleLogin($data, $claveOrganizacion, $tipoModulo);
    }
    
    public function getDb($dataLogin) {
        print_r('En Encuesta_Service_Login::getDb($dataLogin)');
    }
    
    public function getTestConnection($data) {
        $db = $this->appLoginDAO->getTestConnector($data);
        
        return $db;
    }
    
    
    
}
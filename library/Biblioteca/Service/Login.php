<?php
class Biblioteca_Service_Login {
    
    private $appDAO;
    private $loginDAO;
    
    public function __construct() {
        $this->appDAO = new App_Data_DAO_Login();
        //$this->loginDAO = new Biblioteca_Data_DAO_Login();
    }
    
    public function simpleLogin($data, $claveOrganizacion, $tipoModulo) {
        $this->appDAO->simpleLogin($data, $claveOrganizacion, $tipoModulo);
    }
    
    public function getTestConnection($data,$claveOrg = null, $claveModulo = null) {
        $db = $this->appDAO->getTestConnector($data, $claveOrg,$claveModulo);
        
        return $db;
    }
    
    public function getGuestConnection($data) {
        ;
    }
    
}

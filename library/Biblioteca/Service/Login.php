<?php
class Biblioteca_Service_Login {
    
    private $appDAO;
    private $loginDAO;
    
    public function __construct() {
        $auth = Zend_Auth::getInstance();
        if (! $auth->hasIdentity()) {
            ;
        }
        
        $identity = $auth->getIdentity();
        
        $this->appDAO = new App_Data_DAO_Login();
        //$this->loginDAO = new Biblioteca_Data_DAO_Login($identity['adapter']);
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
    
    /**
     * 
     * @param array $dataLogin los datos de sistema para login
     * 
     */
    public function systemLogin($dataLogin) {
        $registry = $this->loginDAO->getRowLoginSystem($dataLogin);
        $testConnector = array();
        
        switch ($dataLogin['tipoAdmin']) {
            case 'BO':
                $testConnector['nickname'] = 'admin';
                $testConnector['password'] = 'Hrodriguezr0800/+';
                break;
                
            case 'SA':
                $testConnector['nickname'] = 'system';
                $testConnector['password'] = 'Hrodriguezr0800/+';
               break;
       }
       
       $conn = $this->appDAO->getSystemConnector($testConnector,'colsagcor16','MOD_BIBLIOTECA');
       return $conn;
    }
    
}

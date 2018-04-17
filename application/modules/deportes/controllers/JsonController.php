<?php

class Deportes_JsonController extends Zend_Controller_Action
{

    private $loginDAO = null;

    private $testConnector = null;

    private $deporteDAO = null;

    private $equipoDAO = null;
    
    private $concursanteDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        $this->loginDAO = new App_Data_DAO_Login();
        
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $this->testConnector = $this->loginDAO->getTestConnector($testData,'colsagcor16', 'MOD_DEPORTES');
        
        if (!is_null($identity)) {
            $this->deporteDAO = new Deportes_Data_DAO_Deporte($identity['adapter']);
            $this->equipoDAO = new Deportes_Data_DAO_Equipo($identity['adapter']);
            $this->concursanteDAO = new Deportes_Data_DAO_Concursante($identity['adapter']);
        }
    }

    public function indexAction()
    {
        // action body
    }

    public function qdeportesAction()
    {
        // action body
    }

    public function qequiposAction()
    {
        // action body
        $idDeporte = $this->getParam('dep');
        $rowsEquipos = $this->equipoDAO->getEquiposByIdDeporte($idDeporte);
        $objsEquipos = $this->equipoDAO->getObjectsEquipos($rowsEquipos); 
        
        echo Zend_Json::encode($objsEquipos);
    }

    public function qintegrantesAction()
    {
        // action body
        $params = $this->getAllParams();
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        
        //print_r($params);
        
        $concursantes = $this->concursanteDAO->getConcursantesByParams($params);
        
        echo Zend_Json::encode($concursantes);
    }


}








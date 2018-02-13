<?php

class Encuesta_IndexController extends Zend_Controller_Action
{
    // =========================================== improvements Noviembre 2017
    // =========================================== improvements Febrero 2018
    private $serviceLogin;
    private $testConnector;
    private $loginDAO = null;
    
    private $cicloEscolarDAO = null;
    private $nivelEducativoDAO = null;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeEncuesta');
        // $this->serviceLogin = new Encuesta_Service_Login();
        $this->serviceLogin = new App_Data_DAO_Login();
        
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $dbConnector = $this->serviceLogin->getTestConnector($testData);
        $this->testConnector = $dbConnector;
        
        $this->cicloEscolarDAO = new Encuesta_Data_DAO_CicloEscolar($dbConnector);
        $this->nivelEducativoDAO = new Encuesta_Data_DAO_NivelEducativo($dbConnector);
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        
        $this->view->ciclosEscolares = $this->cicloEscolarDAO->getAllCiclosEscolares();
        $this->view->nivelesEducativos = $this->nivelEducativoDAO->getAllNivelesEducativos();;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            try {
                $this->serviceLogin->simpleLogin($datos, 'colsagcor16', 'MOD_ENCUESTA');
                $this->_helper->redirector->gotoSimple("index", "dashboard", "encuesta");
            } catch (Exception $e) {
                $message = $e->getMessage();
                $this->view->messageForm = $message;
            }
        }
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        $serviceLogin = $this->serviceLogin;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            // @TODO hardcoded claveOrganizacion y tipoModulo
            try {
                $serviceLogin->simpleLogin($datos, 'colsagcor16', 'MOD_ENCUESTA');
                
                $this->_helper->redirector->gotoSimple("index", "dashboard", "encuesta");
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		
		$this->_helper->redirector->gotoSimple("index", "index", "encuesta");
    }

}

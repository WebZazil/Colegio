<?php

class Evento_IndexController extends Zend_Controller_Action
{
    private $eventoDAO;
    private $loginDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeEvento');
        /*
        $auth = Zend_Auth::getInstance();
        
        $dbAdapter = Zend_Registry::get('zbase');
        $this->eventoDAO = new Evento_Model_DAO_Evento($dbAdapter);
        */
        $this->loginDAO = new App_Data_DAO_Login();
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        
        $testConnector = $this->loginDAO->getTestConnector($testData, 'colsagcor16', 'MOD_BIBLIOTECA');
        $this->eventoDAO = new Evento_Data_DAO_Evento($testConnector);
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            // print_r($datos);
            try{
                $this->loginDAO->simpleLogin($datos, 'colsagcor16', 'MOD_EVENTO');
                $this->_helper->redirector->gotoSimple("index", "dashboard", "evento");
            }catch(Exception $ex){
                $this->view->errorMessage = $ex->getMessage();
            }
        }
    }

    public function logoutAction()
    {
        // action body
    }


}






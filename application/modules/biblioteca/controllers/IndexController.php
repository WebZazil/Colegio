<?php

class Biblioteca_IndexController extends Zend_Controller_Action
{

    private $serviceLogin = null;
    private $testConnector = null;
    private $loginDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->serviceLogin = new Biblioteca_Service_Login();
        
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $this->testConnector = $this->serviceLogin->getTestConnection($testData, 'colsagcor16', 'MOD_BIBLIOTECA');
        
        $this->loginDAO = new Biblioteca_Data_DAO_Login($this->testConnector); 
        $this->_helper->layout->setLayout('homeBiblioteca');
        
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                $this->loginDAO->loginUser($datos);
                $this->_helper->redirector->gotoSimple("index", "udashboard", "biblioteca");
            }catch(Exception $ex){
                //print_r($expression);
                $this->view->errorMessage = $ex->getMessage();
            }
        }
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            // print_r($datos);
            try{
                $this->serviceLogin->simpleLogin($datos, 'colsagcor16', 'MOD_BIBLIOTECA');
                $this->_helper->redirector->gotoSimple("index", "dashboard", "biblioteca");
            }catch(Exception $ex){
                print_r($ex->getMessage());
            }
        }
    }

    public function loginuAction()
    {
        // action body
        
    }

    public function loginbAction()
    {
        // action body
    }


}








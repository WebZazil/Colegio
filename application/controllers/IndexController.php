<?php

class IndexController extends Zend_Controller_Action
{
    private $loginDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->loginDAO = new App_Data_DAO_Login();
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        if($request->isPost()){
            $datos = $request->getPost();
            //print_r($datos);
            try {
                $this->loginDAO->login($datos);
                $this->_helper->redirector->gotoSimple("index", "dashboard");
            } catch (Exception $e) {
                $messages = $e->getMessage();
                $this->view->messages = $messages;
            }
            
        }
    }
    
}




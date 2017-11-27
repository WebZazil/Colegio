<?php

class Encuesta_UsuarioController extends Zend_Controller_Action
{
    private $modulo = "evento";
    private $usuarioDAO;
    

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
            $this->_helper->redirector->gotoSimple("index", "index", $modulo);
        }
        
        //$this->usuarioDAO = new Encuesta_DAO_        
    }

    public function indexAction()
    {
        // action body
    }

    public function settingsAction()
    {
        // action body
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
    }

    public function actionsAction()
    {
        // action body
        $params = $this->getAllParams();
        
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        
        print_r($params);
        
        
        
        
    }


}








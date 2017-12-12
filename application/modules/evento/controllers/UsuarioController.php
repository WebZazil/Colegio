<?php

class Evento_UsuarioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
            $this->_helper->redirector->gotoSimple("index", "index", "evento");
        }
        
        
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
        if (!is_null($auth)) {
            $auth->clearIdentity();
        }
        
        $this->_helper->redirector->gotoSimple("index", "index", "evento");
        
    }


}






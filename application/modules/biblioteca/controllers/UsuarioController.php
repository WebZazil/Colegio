<?php

class Biblioteca_UsuarioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        $identity = $auth->getIdentity();
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
        
        $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");
    }


}






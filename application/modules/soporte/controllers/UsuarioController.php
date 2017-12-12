<?php

class Soporte_UsuarioController extends Zend_Controller_Action {
    
    private $identity;
    private $usuarioDAO;
    
    public function init() {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (is_null($auth)) {
            $this->_helper->redirector->gotoSimple("index", "index", "soporte");
        }
        
        $this->identity = $auth->getIdentity();
        $this->usuarioDAO = new Soporte_Data_DAO_Usuario($this->identity['adapter']);
    }

    public function indexAction() {
        // action body
        // $rol = $this->usuarioDAO;
        
    }

    public function settingsAction() {
        // action body
    }

    public function logoutAction() {
        // action body
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        $this->_helper->redirector->gotoSimple("index", "index", "soporte");
    }


}






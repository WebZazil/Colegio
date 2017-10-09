<?php

class Evento_ConsultaController extends Zend_Controller_Action
{
    private $eventoDAO = null;
    

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
            
        }
        
        $this->eventoDAO = new Evento_Model_DAO_Evento($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $eventos = $this->eventoDAO->getAllEventos();
        
        $this->view->eventos = $eventos;
    }


}


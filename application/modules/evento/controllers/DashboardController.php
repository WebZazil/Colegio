<?php

class Evento_DashboardController extends Zend_Controller_Action
{

    private $eventoDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
            $this->_helper->redirector->gotoSimple("index", "index", "evento");
        }
        
        $this->eventoDAO = new Evento_Model_DAO_Evento($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        
    }

    public function actionsAction()
    {
        // action body
        $eventoDAO = $this->eventoDAO;
        
        $eventos = $this->eventoDAO->getAllEventos();
        $this->view->eventos = $eventos;
    }


}






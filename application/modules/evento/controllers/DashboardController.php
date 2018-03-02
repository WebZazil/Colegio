<?php

class Evento_DashboardController extends Zend_Controller_Action
{

    private $eventoDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (! $auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        
        $identity = $auth->getIdentity();
        
        $this->eventoDAO = new Evento_Data_DAO_Evento($identity['adapter']);
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






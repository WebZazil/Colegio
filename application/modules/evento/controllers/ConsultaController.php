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
            $this->_helper->redirector->gotoSimple("index", "index", "evento");
        }
        
        $this->eventoDAO = new Evento_Data_DAO_Evento($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $idEvento = $this->_getParam("ev");
        $evento = $this->eventoDAO->getEventoById($idEvento);
        
        $this->view->evento = $evento;
    }


}


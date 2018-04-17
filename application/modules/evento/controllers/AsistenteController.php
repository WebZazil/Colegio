<?php

class Evento_AsistenteController extends Zend_Controller_Action
{
    private $asistenteDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            ;
        }
        $identity = $auth->getIdentity();
        
        $this->asistenteDAO = new Evento_Data_DAO_Asistente($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function detalleAction()
    {
        // action body
        $idAsistente = $this->getParam('as');
        
        $asistente = $this->asistenteDAO->getAsistenteById($idAsistente);
        $this->view->asistente = $asistente;
        
        
    }


}




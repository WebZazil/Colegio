<?php

class Encuesta_NormalizeController extends Zend_Controller_Action
{
	private $grupoDAO = null;
    private $opcionDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->opcionDAO = new Encuesta_DAO_Opcion($dataIdentity["adapter"]);
        $this->grupoDAO = new Encuesta_DAO_Grupo($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
    }

    public function nmaxvalgrupoAction()
    {
        // action body
        $grupoDAO = $this->grupoDAO;
        $this->view->mensaje = $grupoDAO->normalizarValorMaximo();
    }

    public function nminvalgrupoAction()
    {
        // action body
        $grupoDAO = $this->grupoDAO;
        $this->view->mensaje = $grupoDAO->normalizarValorMinimo();
    }

    public function preferenciapAction()
    {
        // action body
        try{
        	$this->opcionDAO->normalizarOpciones();
        }catch(Exception $ex){
        	
        }
    }


}








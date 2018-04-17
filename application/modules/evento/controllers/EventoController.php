<?php

class Evento_EventoController extends Zend_Controller_Action
{
    
    private $eventoDAO;

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
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        
        $estatus = $this->eventoDAO->getAllEstatusEvento();
        $this->view->estatus = $estatus;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s', time());
            //print_r($datos);
            
            try{
                $this->eventoDAO->saveEvento($datos);
            }catch (Exception $ex){
                print_r($ex->getMessage());
            }
        }
    }


}




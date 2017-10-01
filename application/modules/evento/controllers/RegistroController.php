<?php

class Evento_RegistroController extends Zend_Controller_Action
{

    private $eventoDAO = null;

    private $registroDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        
        $this->registroDAO = new Evento_Model_DAO_Registro($identity['adapter']);
        $this->eventoDAO = new Evento_Model_DAO_Evento($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        
    }

    public function altaAction()
    {
        // action body
        $idEvento = $this->getParam("ev");
        //if (is_null($idEvento)) $this->_helper->redirector->gotoSimple("alta", "dashboard", "evento");
        
        $evento = $this->eventoDAO->getEventoById($idEvento);
        $this->view->evento = $evento;
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s', time());
            if(array_key_exists('mailist', $datos)){ 
                $datos['mailist'] = 'Y';
            }else{
                $datos['mailist'] = 'N';
            }
            
            //print_r($datos);
            try{
                 $idAsistente = $this->registroDAO->saveAsistente($datos);
                 $this->registroDAO->saveAsistenteEvento($idAsistente, $idEvento);
            }catch (Exception $ex){
                print_r($ex->getMessage());
            }
            
        }
    }


}




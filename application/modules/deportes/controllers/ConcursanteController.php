<?php

class Deportes_ConcursanteController extends Zend_Controller_Action
{

    private $concursanteDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            
        }
        
        $identity = $auth->getIdentity();
        
        $this->concursanteDAO = new Deportes_Data_DAO_Concursante($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        $this->view->estatusConcursante = $this->concursanteDAO->getAllEstatusConcursante();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s',time());
            //print_r($datos);
            
            try {
                $this->concursanteDAO->addConcursante($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        
    }

    public function adminestAction()
    {
        // action body
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s', time());
            //print_r($datos);
            try {
                $this->concursanteDAO->addEstatusConcursante($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->estatusConcursante = $this->concursanteDAO->getAllEstatusConcursante();
    }

    public function configestAction()
    {
        // action body
        $request = $this->getRequest();
        $idEstatusConcursante = $this->getParam('est');
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            
            print_r($datos);
            
            try {
                $this->concursanteDAO->editEstatusConcursante($idEstatusConcursante, $datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->estatus = $this->concursanteDAO->getEstatusConcursanteById($idEstatusConcursante);
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $this->view->estatusConcursante = $this->concursanteDAO->getAllEstatusConcursante();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s',time());
            print_r($datos);
            
            try {
                
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
    }


}








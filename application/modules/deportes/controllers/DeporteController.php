<?php

class Deportes_DeporteController extends Zend_Controller_Action
{

    private $deporteDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            ;
        }
        
        $identity = $auth->getIdentity();
        
        $this->deporteDAO = new Deportes_Data_DAO_Deporte($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s',time());
            print_r($datos);
            
            try {
                $this->deporteDAO->addDeporte($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->deportes = $this->deporteDAO->getAllDeportes();
    }

    public function altaAction()
    {
        // action body
        
        
    }

    public function configdepAction()
    {
        // action body
        $request = $this->getRequest();
        $idDeporte = $this->getParam('dep');
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            
            try {
                $this->deporteDAO->addDeporte($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->deporte = $this->deporteDAO->getDeporteById($idDeporte);
    }


}






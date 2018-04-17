<?php

class Deportes_EquipoController extends Zend_Controller_Action
{

    private $equipoDAO = null;

    private $deporteDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            ;
        }
        $identity = $auth->getIdentity();
        
        $this->equipoDAO = new Deportes_Data_DAO_Equipo($identity['adapter']);
        $this->deporteDAO = new Deportes_Data_DAO_Deporte($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        $this->view->deportes = $this->deporteDAO->getAllDeportes();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['idsConcursantes'] = '';
            $datos['creacion'] = date('Y-m-d H:i:s',time());
            //print_r($datos);
            try {
                $this->equipoDAO->addEquipo($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->estatusEquipo = $this->equipoDAO->getAllEstatus();
        
        
    }

    public function adminestAction()
    {
        // action body
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s');
            
            //print_r($datos);
            try {
                $this->equipoDAO->addEstatusEquipo($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->estatusEquipo = $this->equipoDAO->getAllEstatus();
    }

    public function configestAction()
    {
        // action body
        $request = $this->getRequest();
        $idEstatusEquipo = $this->getParam('est');
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            
            print_r($datos);
            
            try {
                $this->equipoDAO->editEstatusEquipo($idEstatusEquipo, $datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->estatus = $this->equipoDAO->getEstatusById($idEstatusEquipo);
        
    }

    public function configAction()
    {
        // action body
    }

    public function integrantesAction()
    {
        // action body
    }


}










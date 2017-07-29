<?php

class Soporte_IndexController extends Zend_Controller_Action
{
    
    private $equipoDAO = null;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeSoporte');
        $this->equipoDAO = new Soporte_Data_DAO_Equipo();
    }

    public function indexAction()
    {
        // action body
        $equipoDAO = $this->equipoDAO;
        
        $usuarios = $equipoDAO->getUsuarios();
        $ubicaciones = $equipoDAO->getUbicaciones();
        $this->view->usuarios = $usuarios;
        $this->view->ubicaciones = $ubicaciones;
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        
        
        $this->_helper->redirector->gotoSimple("index", "index", "default");
    }

    public function loginAction()
    {
        // action body
    }


}






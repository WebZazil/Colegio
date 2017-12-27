<?php

class Biblioteca_PrestamoController extends Zend_Controller_Action
{

    private $prestamoDAO = null;

    private $usuarioDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            ;
        }
        $identity = $auth->getIdentity();
        
        $this->prestamoDAO = new Biblioteca_Data_DAO_Prestamo($identity['adapter']);
        $this->usuarioDAO = new Biblioteca_Data_DAO_Usuario($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        
    }

    public function devolucionAction()
    {
        // action body
    }

    public function multaAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $idUsuario = $this->getParam('us');
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        
        $this->view->usuario = $usuario;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            
            
            
            
            
            
            
        }
        
        
    }

    public function userAction()
    {
        // action body
        $idUsuario = $this->getParam('us');
        $prestamosUsuario = $this->prestamoDAO->getPrestamosUsuario($idUsuario);
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        
        $this->view->prestamosUsuario = $prestamosUsuario;
        $this->view->usuario = $usuario;
    }


}










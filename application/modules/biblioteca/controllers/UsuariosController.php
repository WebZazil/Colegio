<?php

class Biblioteca_UsuariosController extends Zend_Controller_Action
{

    private $usuarioDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            ;
        }
        $identity = $auth->getIdentity();
        
        $this->usuarioDAO = new Biblioteca_Data_DAO_Usuario($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            $usuarios = $this->usuarioDAO->getUsuariosBibliotecaByTipo($datos['pattern']);
            print_r($usuarios);
            
            $this->view->usuarios = $usuarios;
        }
    }

    public function userAction()
    {
        // action body
    }


}




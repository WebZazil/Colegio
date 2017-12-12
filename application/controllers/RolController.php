<?php

class RolController extends Zend_Controller_Action
{
    private $usuarioService;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (is_null($auth)) {
            ;
        }
        $identity = $auth->getIdentity();
        
        $this->usuarioService = new App_Service_Usuario($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $us = $this->usuarioService;
        $roles = $us->obtenerRoles();
        $this->view->roles = $roles;
    }

    public function editAction()
    {
        // action body
        $us = $this->usuarioService;
        
        $idRol = $this->getParam('rol');
        $request = $this->getRequest();
        
        $rol = $this->usuarioService->obtenerRolById($idRol);
        
        $this->view->rol = $rol;
        if ($request->isPost()){
            $datos = $request->getPost();
            //print_r($datos);
            
            $us->editarRol($datos, $idRol);
        }
    }


}




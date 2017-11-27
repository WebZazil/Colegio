<?php

class UsuarioController extends Zend_Controller_Action
{

    private $organizacion = null;

    private $usuarioService = null;

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (!is_null($identity)) {
            $this->organizacion = $identity['org'];
            $this->usuarioService = new App_Service_Usuario($identity['adapter']);
        }
        
    }

    public function indexAction()
    {
        // action body
        $us = $this->usuarioService;
        $org = $this->organizacion;
        $usuarios = $us->obtenerUsuariosByIdOrganizacion($org['idOrganizacion']);
        
        $this->view->usuarios = $usuarios;
    }

    public function editAction()
    {
        // action body
        $idUsuario = $this->getParam("us");
        
        $us = $this->usuarioService;
        $usuario = $us->obtenerUsuarioById($idUsuario);
        $rolUsuario = $us->obtenerRolUsuario($idUsuario);
        $roles = $us->obtenerRoles();
        
        $this->view->usuario = $usuario;
        $this->view->roles = $roles;
        $this->view->rolUsuario = $rolUsuario;
    }

    public function editrAction()
    {
        // action body
        $us = $this->usuarioService;
        $idUsuario = $this->getParam('us');
        
        $usuario = $us->obtenerUsuarioById($idUsuario);
        $rol = $us->obtenerRolUsuario($idUsuario);
        $roles = $us->obtenerRoles();
        
        
        $this->view->usuario = $usuario;
        $this->view->rolUsuario = $rol;
        $this->view->roles = $roles;
    }
}

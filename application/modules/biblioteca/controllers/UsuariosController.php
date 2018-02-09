<?php

class Biblioteca_UsuariosController extends Zend_Controller_Action
{

    private $usuarioDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
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
            $usuarios = $this->usuarioDAO->getUsuariosBibliotecaByTipo($datos['pattern'],$datos['tipo']);
            //print_r($usuarios);
            
            $this->view->usuarios = $usuarios;
        }
    }

    public function userAction()
    {
        // action body
        $idUsuario = $this->getParam('us');
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        $this->view->usuario = $usuario;
        
        
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        //$auth = Zend_Auth::getInstance();
        //$identity = $auth->getIdentity();
        //$prefijo = rand(500,600);
        //$identity['form'] = array('prefijo' => $prefijo);
        
        //$this->view->prefijo = $prefijo;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datosContacto = array(
                'telefonos' => $datos['telefono'],
                'emails' => $datos['email'],
                'creacion' => date("Y-m-d H:i:s", time())
            );
            
            unset($datos['telefono']);
            unset($datos['email']);
            $datos['estatus'] = 'ACTIVO';
            $datos['creacion'] = date("Y-m-d H:i:s", time());
            //print_r($datos);
            try{
                $idContacto = $this->usuarioDAO->agregarContacto($datosContacto);
                $datos['idContacto'] = $idContacto;
                $this->usuarioDAO->agregarUsuarioBiblioteca($datos);
                $nombreUsuario = $datos['nombres'].', '.$datos['apaterno'].' '.$datos['amaterno'];
                $this->view->messageSuccess = 'El usuario: <strong>'.$nombreUsuario.'</strong> ha sido dado de alta con exito' ;
                
            }catch(Exception $ex){
                print_r($ex->getMessage());
                $this->view->messageFail = 'Ha ocurrido un error: <br /><strong>'.$ex->getMessage().'</strong><br />';
            }
        }
    }

    public function editAction()
    {
        // action body
        $request = $this->getRequest();
        $idUsuario = $this->getParam('us');
        
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        print_r($usuario);
        
        $this->view->usuarios = $usuarios;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            
            
}
    }


}



 



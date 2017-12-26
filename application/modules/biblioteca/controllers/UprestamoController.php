<?php

class Biblioteca_UprestamoController extends Zend_Controller_Action
{
    private $identity;
    private $prestamoDAO;
    private $estatusLibroDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('dashbuser');
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            
        }
        $identity = $auth->getIdentity();
        $this->identity = $identity;
        
        $this->prestamoDAO = new Biblioteca_Data_DAO_User_Prestamo($identity['adapter']);
        $this->estatusLibroDAO = new Biblioteca_Data_DAO_User_EstatusLibro($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $this->view->breadcrumbData = array(
            'controllerName'=>'Prestamo',
            'actionName' => 'Info'
        );
        
        $this->view->estatusLibro = $this->estatusLibroDAO->getEstatusLibroUsuario();
        $this->view->identity = $this->identity;
        
        $request = $this->getRequest();
        if($request->isPost()){
            $datos = $request->getPost();
            $usuario = $this->identity['user'];
            //print_r($datos);print_r('<br />');
            //print_r($usuario);
            $mensaje = '';
            $prestamos = $this->prestamoDAO->getPrestamosUsuario($usuario['id'], $datos['estatus']);
            if (empty($prestamos)) {
                $mensaje = 'No hay libros en prestamo';
            }
            $this->view->message = $mensaje;
            $this->view->prestamos = $prestamos;
        }
        
    }

    public function multaAction()
    {
        // action body
        $this->view->breadcrumbData = array(
            'controllerName'=>'Prestamo',
            'actionName' => 'Multa'
        );
    }

    public function historialAction()
    {
        // action body
        $this->view->breadcrumbData = array(
            'controllerName'=>'Prestamo',
            'actionName' => 'Historial'
        );
    }


}

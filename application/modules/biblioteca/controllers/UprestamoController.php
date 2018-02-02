<?php

class Biblioteca_UprestamoController extends Zend_Controller_Action
{
    private $identity;
    private $prestamoDAO;
    private $multaDAO;
    //private $estatusLibroDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('dashbuser');
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            
        }
        $identity = $auth->getIdentity();
        $this->identity = $identity;
        
        //$this->prestamoDAO = new Biblioteca_Data_DAO_User_Prestamo($identity['adapter']);
        $this->prestamoDAO = new Biblioteca_Data_DAO_Prestamo($identity['adapter']);
        $this->multaDAO = new Biblioteca_Data_DAO_Multa($identity['adapter']);
        //$this->estatusLibroDAO = new Biblioteca_Data_DAO_User_EstatusLibro($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $this->view->breadcrumbData = array(
            'controllerName'=>'Prestamo',
            'actionName' => 'Info'
        );
        $estatusPrestamo = $this->prestamoDAO->getAllEstatusPrestamo();
        
        $this->view->estatusLibro = $estatusPrestamo;
        $mensaje = 'Realice una consulta';
        $this->view->identity = $this->identity;
        
        $request = $this->getRequest();
        if($request->isPost()){
            $datos = $request->getPost();
            $usuario = $this->identity['user'];
            //print_r($datos);print_r('<br />');
            //print_r($usuario);
            //print_r($estatusPrestamo);
            
            $prestamos = $this->prestamoDAO->getObjectPrestamosUsuario($usuario['id'], $datos['estatus']);
            if (empty($prestamos)) {
                $estatus = '';
                foreach ($estatusPrestamo as $es) {
                    if ($es['idEstatusPrestamo'] == $datos['estatus']) {
                        $estatus = $es;
                        //print_r($es);
                    }
                }
                $mensaje = 'No hay libros en <strong>'.$estatus['estatusPrestamo'].'</strong>';
            }
            
            $this->view->prestamos = $prestamos;
            $this->view->estatusConsultado = $datos['estatus'];
        }
        $this->view->message = $mensaje;
    }

    public function multaAction()
    {
        // action body
        $request = $this->getRequest();
        
        $this->view->breadcrumbData = array(
            'controllerName'=>'Prestamo',
            'actionName' => 'Multa'
        );
        
        $multaDAO = $this->multaDAO;
        
        $mensaje = 'Realice una consulta';
        $this->view->estatusMulta = $multaDAO->getAllEstatusMulta();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            
            $multas = $multaDAO->getMultaByIdPrestamo($idPrestamo);
            
        }
        
        
        $this->view->message = $mensaje;
        
        $this->view->multas = array();
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

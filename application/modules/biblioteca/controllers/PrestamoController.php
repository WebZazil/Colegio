<?php

class Biblioteca_PrestamoController extends Zend_Controller_Action
{

    private $prestamoDAO = null;
    private $usuarioDAO = null;
    private $recursoDAO = null;
    private $materialDAO = null;
    private $coleccionDAO = null;
    private $clasificacionDAO = null;
    private $ejemplarDAO = null;
    private $multaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        
        $identity = $auth->getIdentity();
        
        $this->prestamoDAO = new Biblioteca_Data_DAO_Prestamo($identity['adapter']);
        $this->usuarioDAO = new Biblioteca_Data_DAO_Usuario($identity['adapter']);
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
        
        $this->materialDAO = new Biblioteca_Data_DAO_Material($identity['adapter']);
        $this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($identity['adapter']);
        $this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($identity['adapter']);
        $this->ejemplarDAO = new Biblioteca_Data_DAO_Ejemplar($identity['adapter']);
        $this->multaDAO = new Biblioteca_Data_DAO_Multa($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            
        }
        
    }

    public function devolucionAction()
    {
        // action body
        $idUsuario = $this->getParam('us');
        $idPrestamo = $this->getParam('po');
        
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        $prestamo = $this->prestamoDAO->getRowPrestamoById($idPrestamo);
        
        $this->prestamoDAO->devolverPrestamo($prestamo['idPrestamo']);
        $this->prestamoDAO->setEstatusInventarioEjemplar($prestamo['idInventario'], 'DISPONIBLE');
        $this->prestamoDAO->agregarPrestamoCopia($prestamo['idInventario']);
        
        $this->_helper->redirector->gotoSimple("user", "prestamo", "biblioteca",array('us'=>$idUsuario));
    }

    public function multaAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $idUsuario = $this->getParam('us');
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        
        $prestamos = $this->prestamoDAO->getObjectPrestamosUsuario($idUsuario);
        
        $contenedor = array();
        
        foreach ($prestamos as $prestamo){
            $obj = array();
            $obj['prestamo'] = $prestamo;
            //$obj['estatus'] = $this->prestamoDAO->getEstatusPrestamoById($prestamo['idEstatusPrestamo']);
            $copia = $this->ejemplarDAO->getCopiaEjemplarByIdCopia($prestamo['prestamo']['idInventario']);
            $obj['copia'] = $copia;
            $ejemplar = $this->ejemplarDAO->getObjectEjemplar($copia['idEjemplar']);
            $obj['ejemplar'] = $ejemplar;
            $obj['recurso'] = $this->recursoDAO->getObjectRecurso($ejemplar['ejemplar']['idRecurso']);
            
            $contenedor[] = $obj;
        }
        
        $this->view->usuario = $usuario;
        $this->view->contenedor = $contenedor;
    }

    public function userAction()
    {
        // action body
        $idUsuario = $this->getParam('us');
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        
        $prestamos = $this->prestamoDAO->getObjectPrestamosUsuario($idUsuario);
        
        $contenedor = array();
        
        foreach ($prestamos as $prestamo){
            $obj = array();
            $obj['prestamo'] = $prestamo;
            $copia = $this->ejemplarDAO->getCopiaEjemplarByIdCopia($prestamo['prestamo']['idInventario']);
            $obj['copia'] = $copia;
            $ejemplar = $this->ejemplarDAO->getObjectEjemplar($copia['idEjemplar']);
            $obj['ejemplar'] = $ejemplar;
            $obj['recurso'] = $this->recursoDAO->getObjectRecurso($ejemplar['ejemplar']['idRecurso']);
            
            $contenedor[] = $obj;
        }
        
        $contMultas = array();
        $prestamos = $this->prestamoDAO->getPrestamosUsuario($idUsuario);
        
        foreach ($prestamos as $prestamo){
            //print_r($prestamo);
            $multa = $this->multaDAO->getMultaByIdPrestamo($prestamo['idPrestamo']);
            if (!is_null($multa)) {
                $contMultas[] = $multa;
            }
        }
        
        $this->view->multas = $contMultas;
        $this->view->contenedor = $contenedor;
        $this->view->usuario = $usuario;
    }

    public function prestamoAction()
    {
        // action body
        $idCopia = $this->getParam('cp');
        $idUsuario = $this->getParam('us');
        
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        $copia = $this->ejemplarDAO->getCopiaEjemplarByIdCopia($idCopia);
        $estatusPrestado = $this->prestamoDAO->getEstatusPrestamoByEstatus('PRESTAMO');
        
        $ejemplar = $this->ejemplarDAO->getObjectEjemplar($copia['idEjemplar']);
        $recurso = $this->recursoDAO->getRecursoById($ejemplar['ejemplar']['idRecurso']);
        
        $hoy = date('Y-m-d',time());
        $entrega = date('Y-m-d',strtotime('+1 week'));
        
        $datos = array(
            'idInventario' => $idCopia,
            'idEstatusPrestamo' => $estatusPrestado['idEstatusPrestamo'],
            'idUsuario' => $idUsuario,
            'fechaPrestamo' => $hoy,
            'fechaDevolucion' => $entrega,
            'fechaVencimiento' => $entrega,
            'creacion' => date('Y-m-d h:i:s',time())
        );
        
        $this->prestamoDAO->agregarPrestamoUsuario($datos);
        $this->prestamoDAO->setEstatusInventarioEjemplar($idCopia, 'NO DISPONIBLE');
        $this->_helper->redirector->gotoSimple("alta", "prestamo", "biblioteca",array('us'=>$idUsuario));
        
    }

    public function prestamotAction()
    {
        // action body
        $request = $this->getRequest();
        //$idRecurso = $this->getParam('rc');
        $idCopia = $this->getParam('cp');
        $idUsuario = $this->getParam('us');
        
        //$recurso = $this->recursoDAO->getRecursoById($idRecurso);
        $copia = $this->ejemplarDAO->getCopiaEjemplarByIdCopia($idCopia);
        $prestamo = $this->prestamoDAO->getPrestamoByIdCopia($idCopia);
        $ejemplar = $this->ejemplarDAO->getObjectEjemplar($copia['idEjemplar']);
        $recurso = $this->recursoDAO->getRecursoById($ejemplar['ejemplar']['idRecurso']);
        
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        $estatusPrestamo = $this->prestamoDAO->getEstatusPrestamoByEstatus('PRESTAMO');
        
        $this->view->recurso = $recurso;
        $this->view->usuario = $usuario;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            unset($datos['idRecurso']);
            $datos['idInventario'] = $idCopia;
            $datos['idEstatusPrestamo'] = $estatusPrestamo['idEstatusPrestamo'];
            $datos['fechaVencimiento'] = $datos['fechaDevolucion'];
            $datos['creacion'] = date('Y-m-d h:i:s',time());
            //print_r($datos);
            //return ;
            try {
                $this->prestamoDAO->agregarPrestamoUsuario($datos);
                $this->prestamoDAO->setEstatusInventarioEjemplar($idCopia, 'NO DISPONIBLE');
                //$this->recursoDAO->setEstatus('PRESTAMO', $idRecurso);
                $this->_helper->redirector->gotoSimple("alta", "prestamo", "biblioteca",array('us'=>$idUsuario));
                
                $this->view->messageSuccess = 'El prestamo de <strong>'.$recurso['titulo'].'</strong> se ha realizado correctamente';
            } catch (Exception $e) {
                //print_r($e->getMessage());
                $this->view->messageFail = 'Fallo el prestamo de <strong>'.$recurso['titulo'].'</strong><br /><strong>'.$e->getMessage().'</strong>';
            }
            
        }
        
    }


}






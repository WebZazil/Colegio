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
        
        $hoy = date('Y-m-d',time());
        $entrega = date('Y-m-d',strtotime('+1 week'));
        
        $datos = array(
            'idInventario' => $idCopia['idInventario'],
            'idEstatusPrestamo' => $estatusPrestado['idEstatusPrestamo'],
            'idUsuario' => $idUsuario,
            'fechaPrestamo' => $hoy,
            'fechaDevolucion' => $entrega,
            'fechaVencimiento' => $entrega,
            'creacion' => date('Y-m-d h:i:s',time())
        );
        
        try {
            $this->prestamoDAO->agregarPrestamoUsuario($datos);
            //$this->recursoDAO->setEstatusRecurso('PRESTAMO', $idRecurso);
            $this->_helper->redirector->gotoSimple("alta", "prestamo", "biblioteca",array('us'=>$idUsuario));
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        
    }

    public function prestamotAction()
    {
        // action body
        $request = $this->getRequest();
        $idRecurso = $this->getParam('rc');
        $idUsuario = $this->getParam('us');
        
        $recurso = $this->recursoDAO->getRecursoById($idRecurso);
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        
        $this->view->recurso = $recurso;
        $this->view->usuario = $usuario;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            $datos['creacion'] = date('Y-m-d h:i:s',time());
            try {
                $this->prestamoDAO->agregarPrestamoUsuario($datos);
                $this->recursoDAO->setEstatusRecurso('PRESTAMO', $idRecurso);
                $this->_helper->redirector->gotoSimple("alta", "prestamo", "biblioteca",array('us'=>$idUsuario));
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
            
        }
        
    }


}






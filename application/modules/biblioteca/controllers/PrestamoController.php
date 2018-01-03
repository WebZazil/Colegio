<?php

class Biblioteca_PrestamoController extends Zend_Controller_Action
{

    private $prestamoDAO = null;

    private $usuarioDAO = null;

    private $recursoDAO = null;
    private $materialDAO;
    private $coleccionDAO;
    private $clasificacionDAO;

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
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
        
        $this->materialDAO = new Biblioteca_Data_DAO_Material($identity['adapter']);
        $this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($identity['adapter']);
        $this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($identity['adapter']);
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
        
        $this->view->usuario = $usuario;
        $prestamos = $this->prestamoDAO->getPrestamosUsuario($idUsuario);
        $estatusRecurso = $this->recursoDAO->getEstatusRecurso();
        $materiales = $this->materialDAO->getAllMateriales();
        $colecciones = $this->coleccionDAO->getAllColecciones();
        $clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        $container = array();
        
        foreach ($prestamos as $prestamo){
            $obj = array();
            $recurso = $this->recursoDAO->getRecursoById($prestamo['idRecurso']);
            $obj['recurso'] = $recurso;
            # Recorremos Materiales
            foreach ($materiales as $material){
                if($material['idMaterial'] == $recurso['idMaterial']){
                    $obj['material'] = $material;
                }
            }
            # Recorremos EstatusRecurso
            foreach ($estatusRecurso as $estatus){
                if($estatus['idEstatusRecurso'] == $recurso['idEstatusRecurso']){
                    $obj['estatus'] = $estatus;
                }
            }
            
            # Recorremos Coleccion
            foreach ($colecciones as $coleccion){
                if($coleccion['idColeccion'] == $recurso['idColeccion']){
                    $obj['coleccion'] = $coleccion;
                }
            }
            
            # Recorremos Clasificaciones
            foreach ($clasificaciones as $clasificacion){
                if($clasificacion['idClasificacion'] == $recurso['idClasificacion']){
                    $obj['clasificacion'] = $clasificacion;
                }
            }
            
            $container[] = $obj;
            
        }
        
        $this->view->recursos = $container;
    }

    public function userAction()
    {
        // action body
        $idUsuario = $this->getParam('us');
        $prestamos = $this->prestamoDAO->getPrestamosUsuario($idUsuario);
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        
        $estatusRecurso = $this->recursoDAO->getEstatusRecurso();
        $materiales = $this->materialDAO->getAllMateriales();
        $colecciones = $this->coleccionDAO->getAllColecciones();
        $clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        $container = array();
        
        foreach ($prestamos as $prestamo){
            $obj = array();
            $obj['prestamo'] = $prestamo;
            $recurso = $this->recursoDAO->getRecursoById($prestamo['idRecurso']);
            $obj['recurso'] = $recurso;
            # Recorremos Materiales
            foreach ($materiales as $material){
                if($material['idMaterial'] == $recurso['idMaterial']){
                    $obj['material'] = $material;
                }
            }
            # Recorremos EstatusRecurso
            foreach ($estatusRecurso as $estatus){
                if($estatus['idEstatusRecurso'] == $recurso['idEstatusRecurso']){
                    $obj['estatus'] = $estatus;
                }
            }
            
            # Recorremos Coleccion
            foreach ($colecciones as $coleccion){
                if($coleccion['idColeccion'] == $recurso['idColeccion']){
                    $obj['coleccion'] = $coleccion;
                }
            }
            
            # Recorremos Clasificaciones
            foreach ($clasificaciones as $clasificacion){
                if($clasificacion['idClasificacion'] == $recurso['idClasificacion']){
                    $obj['clasificacion'] = $clasificacion;
                }
            }
            
            $container[] = $obj;
            
        }
        
        $this->view->prestamosUsuario = $container;
        $this->view->usuario = $usuario;
    }

    public function prestamoAction()
    {
        // action body
        $idRecurso = $this->getParam('rc');
        $idUsuario = $this->getParam('us');
        
        $recurso = $this->recursoDAO->getRecursoById($idRecurso);
        $usuario = $this->usuarioDAO->getUsuarioBibliotecaById($idUsuario);
        
        $hoy = date('Y-m-d',time());
        $entrega = date('Y-m-d',strtotime('+1 week'));
        
        // print_r('Hoy: '.$hoy.'<br /><br />');
        // print_r('Devolucion: '.$entrega.'<br /><br />');
        
        $datos = array(
            'idRecurso' => $idRecurso,
            'idUsuario' => $idUsuario,
            'fechaPrestamo' => $hoy,
            'fechaDevolucion' => $entrega,
            'fechaVencimiento' => $entrega,
            'creacion' => date('Y-m-d h:i:s',time())
        );
        try {
            $this->prestamoDAO->agregarPrestamoUsuario($datos);
            $this->recursoDAO->setEstatusRecurso('PRESTAMO', $idRecurso);
            $this->_helper->redirector->gotoSimple("alta", "prestamo", "biblioteca",array('us'=>$idUsuario));
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        
    }


}




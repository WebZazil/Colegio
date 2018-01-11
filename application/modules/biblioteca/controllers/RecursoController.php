<?php

class Biblioteca_RecursoController extends Zend_Controller_Action
{

    private $recursoDAO = null;

    private $materialDAO = null;

    private $coleccionDAO = null;

    private $clasificacionDAO = null;

    private $autorDAO = null;

    private $inventarioDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");
        }
        $identity = $auth->getIdentity();
        
		$this->recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
		$this->materialDAO = new Biblioteca_Data_DAO_Material($identity['adapter']);
		$this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($identity['adapter']);
		$this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($identity['adapter']);
		$this->autorDAO = new Biblioteca_Data_DAO_Autor($identity['adapter']);
		
		$this->inventarioDAO = new Biblioteca_Data_DAO_Inventario($identity['adapter']);
    }

    public function indexAction()
    {
        // action body: Actualizado Enero 2018
        $request = $this->getRequest();
        
        $this->view->materiales = $this->materialDAO->getAllMateriales();
        $this->view->colecciones = $this->coleccionDAO->getAllColecciones();
        $this->view->clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos); print_r('<br /><br />');
            foreach ($datos as $key => $value) {
                if ($value == "0" || $value == '') {
                    unset($datos[$key]);
                }
            }
            
            $recursos = $this->recursoDAO->getRecursoByParams($datos);
            
            $contenedor = array();
            
            foreach ($recursos as $recurso) {
                $obj = $this->inventarioDAO->getObjectRecurso($recurso['idRecurso']);
                $contenedor[] = $obj;
            }
            
            $this->view->recursos = $contenedor;
        }else{
            $this->view->recursos = array();
        }
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        
        //$this->view->estatusRecurso = $this->recursoDAO->getEstatusRecurso();
        $this->view->autores = $this->autorDAO->getAllAutores();
        $this->view->materiales = $this->materialDAO->getAllMateriales();
        $this->view->colecciones = $this->coleccionDAO->getAllColecciones();
        $this->view->clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
		if($request->isPost()) {
		    $datos = $request->getPost();
		    $datos['creacion'] = date('Y-m-d H:i:s', time());
		    
		    try{
		        $idRecurso = $this->recursoDAO->agregarRecurso($datos);
		        $this->view->messageSuccess ="El recurso: <strong>".$datos['recurso']."</strong> ha sido agregado";
		    }catch(Exception $ex){
		        $this->view->messageFail = "El recurso: <strong>".$datos['recurso']."</strong> no ha sido agregado. Error: <strong>".$ex->getMessage()."<strong>";
		    }
		}
    }

    public function adminAction()
    {
        // action body
        $request = $this->getRequest();
        $idRecurso = $this->getParam('re');
        
        $recurso = $this->recursoDAO->getRecursoById($idRecurso);
        $this->view->autores = $this->autorDAO->getAllAutores();
        $this->view->materiales = $this->materialDAO->getAllMateriales();
        $this->view->colecciones = $this->coleccionDAO->getAllColecciones();
        $this->view->clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        $this->view->recurso = $recurso;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            
        }
        
    }

    public function recursoAction()
    {
        // action body
        $idRecurso = $this->getParam('rc');
        $recurso = $this->recursoDAO->getRecursoById($idRecurso);
        
        $this->view->recurso = $recurso;
    }


}





<?php

class Biblioteca_RecursoController extends Zend_Controller_Action
{

    private $recursoDAO = null;

    private $materialDAO = null;

    private $coleccionDAO = null;

    private $clasificacionDAO = null;

    private $autorDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		//$this->recursoDAO = new Biblioteca_DAO_Recurso($dbAdapter);
		$this->recursoDAO = new Biblioteca_Data_DAO_Recurso($dbAdapter);
		$this->materialDAO = new Biblioteca_Data_DAO_Material($dbAdapter);
		$this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($dbAdapter);
		$this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($dbAdapter);
		$this->autorDAO = new Biblioteca_Data_DAO_Autor($dbAdapter);
		
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        $materialDAO = $this->materialDAO;
		$coleccionDAO = $this->coleccionDAO;
		$clasificacionDAO = $this->clasificacionDAO;
		$autorDAO = $this->autorDAO;
		
        //---------------------------------------------------------
		//Obtenemos los recursos
		$recursos = $this->recursoDAO->getAllTableRecursos();
		
		$materiales = $this->materialDAO->getAllMateriales();
		$this->view->materiales = $materiales;
		
		$colecciones = $this->coleccionDAO->getAllColecciones();
		$this->view->colecciones = $colecciones;
		
		$autores = $this->autorDAO->getAllAutores();
		$this->view->autores = $autores;
		
		$clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
		$this->view->clasificaciones = $clasificaciones;
		
		$obj = array();
		
		foreach ($recursos as $recurso) {
			$contenedor = array();
			$contenedor["recurso"] = $recurso;
			$contenedor["material"] = $materialDAO->getMaterialById($recurso["idMaterial"]);
			$contenedor["coleccion"] = $coleccionDAO->getColeccionById($recurso["idColeccion"]);
			$contenedor["clasificacion"] = $clasificacionDAO->getClasificacionById($recurso["idClasificacion"]);
			
			$obj[] = $contenedor;
		}
		
		$this->view->recursos = $obj;
		
		
		if($request->isPost()){
			$datos = $request->getPost();
			//print_r($datos); print_r("<br />");
			
			foreach ($datos as $key => $value) {
				if ($value == "0" || $value == '') {
					unset($datos[$key]);
				}
			}
			//print_r($datos);
			$resources = $this->recursoDAO->getRecursoByParams($datos);
			//$resources = array();
			if(!empty($resources)){
				$container = array();
				
				foreach ($resources as $resource) {
					$o = array();	// Un recurso y sus parametros
					
					$o["recurso"] = $resource;
					$o["material"] = $materialDAO->getMaterialById($resource["idMaterial"]);
					$o["coleccion"] = $coleccionDAO->getColeccionById($resource["idColeccion"]);
					$o["clasificacion"] = $clasificacionDAO->getClasificacionById($resource["idClasificacion"]);
					//$o["autor"] = $autorDAO->getAutorById($resource["idAutor"]);
					
					$container[] = $o;
				}
				
				$this->view->resources = $container;
			}else{
				$this->view->resources = array();
			}
			
		}else{
			$this->view->resources = array();
		}
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        
        $this->view->estatusRecurso = $this->recursoDAO->getEstatusRecurso();
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
        $this->view->estatusRecurso = $this->recursoDAO->getEstatusRecurso();
        $this->view->materiales = $this->materialDAO->getAllMateriales();
        $this->view->colecciones = $this->coleccionDAO->getAllColecciones();
        $this->view->clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        $this->view->recurso = $recurso;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            
        }
        
    }


}



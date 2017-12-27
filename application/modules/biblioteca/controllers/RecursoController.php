<?php

class Biblioteca_RecursoController extends Zend_Controller_Action
{
	
	private $recursoDAO;
	private $materialDAO;
	private $coleccionDAO;
	private $clasificacionDAO;
	private $autorDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->recursoDAO = new Biblioteca_DAO_Recurso($dbAdapter);   
		$this->materialDAO = new Biblioteca_Data_DAO_Material($dbAdapter);
		$this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($dbAdapter);
		$this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($dbAdapter);
		$this->autorDAO = new Biblioteca_Data_DAO_Autor($dbAdapter);
		
	}

    public function indexAction()
    {
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
		
		
		
		//	$this->view->materiales = $materiales;
        $obj = array();
		//$recursos = $this->recursoDAO->getAllTableRecursos();
        //print_r($recursos);
		
		foreach ($recursos as $recurso) {
			
		    //print_r($recurso["idMaterial"]); print_r("<br />");
			$contenedor = array();
			$contenedor["recurso"] = $recurso;
			$contenedor["material"] = $materialDAO->getMaterialById($recurso["idMaterial"]);
			$contenedor["coleccion"] = $coleccionDAO->getColeccionById($recurso["idColeccion"]);
			$contenedor["clasificacion"] = $clasificacionDAO->getClasificacionById($recurso["idClasificacion"]);
			//$contenedor["autor"] = $autorDAO->getAutorById($recurso["idAutor"]);
			
			
			$obj[] = $contenedor;
	
		}
		
		/*foreach ($obj as $contenedor) {
			
			//print_r($contenedor); print_r("<br />");
			print_r("<br />");
			print_r($contenedor["recurso"]); print_r("<br />");
			print_r($contenedor["material"]); print_r("<br />");
			print_r($contenedor["coleccion"]); print_r("<br />");
			print_r($contenedor["clasificacion"]); print_r("<br />");
			print_r($contenedor["autor"]); print_r("<br />");
			
			$this->view->contenedor = $contenedor;
		}*/
		
		$this->view->recursos = $obj;
		$request = $this->getRequest();
		
		if($request->isPost()){
			$datos = $request->getPost();
			print_r($datos); print_r("<br />");
			
			foreach ($datos as $key => $value) {
				if ($value == "0") {
					unset($datos[$key]);
				}
			}
			print_r($datos);
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
					$o["autor"] = $autorDAO->getAutorById($resource["idAutor"]);
					
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
        $this->view->materiales = $this->materialDAO->getAllMateriales();
        $this->view->colecciones = $this->coleccionDAO->getAllColecciones();
        $this->view->clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        
		
		if($request->isPost()){
		    $datos = $request->getPost();
		    
		    print_r($datos);
		    
		    //$recurso = new Biblioteca_Model_Recurso($datos);
		    /*
		    try{
		        
		        $this->recursoDAO->agregarRecurso($recurso);
		        $this->view->messageSuccess ="El recurso: <strong>".$recurso->getTitulo()."</strong> ha sido agregado";
		    }catch(Exception $ex){
		        $this->view->messageFail = "El recurso: <strong>".$recurso->getTitulo()."</strong> no ha sido agregado. Error: <strong>".$ex->getMessage()."<strong>";
		    }
		    */
		}
        
    }


}




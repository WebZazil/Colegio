<?php

class Encuesta_ConjuntoController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;

    private $asignacionDAO = null;

    private $encuestaDAO = null;

    private $grupoDAO = null;

    private $registroDAO = null;

    private $materiaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodquery");
		
		$this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
		
		$this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($dbAdapter);
		$this->encuestaDAO = new Encuesta_DAO_Encuesta($dbAdapter);
		
		$this->grupoDAO = new Encuesta_DAO_Grupos($dbAdapter);
		$this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
		$this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        $this->view->conjuntos = $this->evaluacionDAO->getAllConjuntos();
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $formulario = new Encuesta_Form_AltaConjunto;
		$this->view->formulario = $formulario;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				
				//print_r($datos);
				try{
					$this->evaluacionDAO->addConjuntoEvaluador($datos);
					$this->view->messageSuccess = "El conjunto <strong>".$datos["nombre"]."</strong> ha sido creado correctamente.";
				}catch(Exception $ex){
					$this->view->messageFail = "Ha ocurrido un error: <strong>".$ex->getMessage()."</strong>";
				}
				/**/
			}
		}
		
    }

    public function adminAction()
    {
        // action body
        $idConjunto = $this->getParam("idConjunto");
		$conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
		$encuestas = $this->encuestaDAO->getAllEncuestas();
		$asignacionesGrupo =  $this->asignacionDAO->obtenerAsignacionesGrupo($conjunto["idGrupoEscolar"]);
		$asignacionesConjunto = $this->evaluacionDAO->getAsignacionesByIdConjunto($idConjunto);
		//print_r($asignacionesConjunto);
		// disponibles = grupo - conjunto i.e. todas - asignadas.
		$asignacionesDisponibles = array();
		
		$asignaciones = array();
		/*
		foreach ($asignacionesConjunto as $asignacionC) {
			foreach ($asignacionesGrupo as $asignacionG) {
				//si la asignacion
				print_r($asignacionC["idAsignacionGrupo"]);
				if($asignacionC["idAsignacionGrupo"] != $asignacionG["idAsignacionGrupo"]) $asignaciones[] = $asignacionG;
			}
		}*/
		print_r($asignacionesConjunto);
		foreach ($asignacionesGrupo as $asignacionG) {
			//print_r($asignacionG);
			if(empty($asignacionesConjunto)){
				//print_r("conjunto vacio");
				$asignaciones = $asignacionesGrupo;
			}else{
				foreach ($asignacionesConjunto as $asignacionC) {
					if($asignacionC["idAsignacionGrupo"] != $asignacionG["idAsignacionGrupo"]) $asignaciones[] = $asignacionG;
				}
			}
		}
		
		//print_r($asignaciones);
		
		//print_r($asignacionesGrupo);
		
		foreach ($asignaciones as $asignacion) {
			$obj = array();
			$obj["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
			$obj["docente"] = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
			$asignacionesDisponibles[$asignacion["idAsignacionGrupo"]] = $obj;
		}
		//print_r($asignacionesDisponibles);
		
		$this->view->conjunto = $conjunto;
		$this->view->asignacionesDisponibles = $asignacionesDisponibles;
		$this->view->asignacionesConjunto = $asignacionesConjunto;
    }

    public function evaluadoresAction()
    {
        // action body
        $idConjunto = $this->getParam("idConjunto");
		$conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
		$evaluadores = $this->evaluacionDAO->getEvaluadoresByIdConjunto($idConjunto);
		$this->view->evaluadores = $evaluadores;
		$this->view->conjunto = $conjunto;
		//$this->evaluacionDAO->getEvaluadoresByIdConjunto; // Evaluadores registrados en un conjunto
        
    }

    public function agregarevalAction()
    {
        // action body
        $request = $this->getRequest();
		$idConjunto = $this->getParam("idConjunto");
		if($request->isPost()){
			$datos = $request->getPost();
			print_r($datos);
		}
        
    }

    public function agregarasignAction() {
        // action body
        $request = $this->getRequest();
		$idConjunto = $this->getParam("idConjunto");
        //print_r($datos);
		if ($request->isPost()) {
			$datos = $request->getPost();
			
			try{
				$this->evaluacionDAO->asociarAsignacionAConjunto($idConjunto,$datos["idAsignacion"]);
			}catch(Exception $ex){
				print_r($ex->getMessage());
			}
		}
    }


}












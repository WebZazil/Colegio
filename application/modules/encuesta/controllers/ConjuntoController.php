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
		$this->view->conjunto = $conjunto;
		$this->view->encuestas = $encuestas;
		
		$asignacionesGrupo =  $this->asignacionDAO->obtenerAsignacionesGrupo($conjunto["idGrupoEscolar"]);
		$asignacionesConjunto = $this->evaluacionDAO->getAsignacionesByIdConjunto($idConjunto);
		// disponibles = grupo - conjunto i.e. todas - asignadas.
		
		//print_r($asignacionesConjunto);
		$asignacionesDisponibles = array();
		//print_r($asignacionesGrupo);
		
		foreach ($asignacionesGrupo as $asignacionG) {
			//print_r($asignacionG);
			foreach ($asignacionesConjunto as $asignacionC) {
				if($asignacionC["idAsignacionGrupo"] == $asignacionG["idAsignacionGrupo"]){
					//print_r("Relacionada<br />");
					
				}else{
					//print_r("No Relacionada<br />");
					$asignacionesDisponibles[] = $asignacionG;
				}
			}
		}
		// 
		$asignacionesC = array();
		$asignacionesD = array();
		
		foreach ($asignacionesConjunto as $asignacion) {
			$obj = array();
			$obj["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
			$obj["docente"] = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
			$asignacionesC[$asignacion["idAsignacionGrupo"]] = $obj;
		}
		
		foreach ($asignacionesDisponibles as $asignacion) {
			$obj = array();
			$obj["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
			$obj["docente"] = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
			$asignacionesD[$asignacion["idAsignacionGrupo"]] = $obj;
		}
		
		$this->view->asignacionesDisponibles = $asignacionesD;
		$this->view->asignacionesConjunto = $asignacionesC;
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
			//print_r($datos);
			try{
				$this->evaluacionDAO->asociarEvaluacionAConjunto($idConjunto, $datos["idEncuesta"]);
				$this->_helper->redirector->gotoSimple("admin", "conjunto", "encuesta", array("idConjunto"=>$idConjunto));
			}catch(Exception $ex){
				print_r($ex->getMessage());
			}
		}
        
    }

    public function agregarasignAction()
    {
        // action body
        $request = $this->getRequest();
		$idConjunto = $this->getParam("idConjunto");
        //print_r($datos);
		if ($request->isPost()) {
			$datos = $request->getPost();
			print_r($datos);
			try{
				$this->evaluacionDAO->asociarAsignacionAConjunto($idConjunto,$datos["idAsignacion"]);
				$this->_helper->redirector->gotoSimple("admin", "conjunto", "encuesta", array("idConjunto"=>$idConjunto));
			}catch(Exception $ex){
				print_r($ex->getMessage());
			}
		}
    }

    public function evalsAction()
    {
        // action body
        $idConjunto = $this->getParam("idConjunto");
		$evaluaciones = $this->evaluacionDAO->getEvaluacionesByIdConjunto($idConjunto);
		$this->view->evaluaciones = $evaluaciones;
		
    }


}














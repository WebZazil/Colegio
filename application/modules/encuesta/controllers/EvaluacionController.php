<?php

class Encuesta_EvaluacionController extends Zend_Controller_Action
{

    private $asignacionDAO = null;

    private $gruposDAO = null;

    private $evaluacionDAO = null;

    private $encuestaDAO = null;

    private $generador = null;

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodquery");
        
        $this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($dbAdapter);
		$this->gruposDAO = new Encuesta_DAO_Grupos($dbAdapter);
		$this->encuestaDAO = new Encuesta_DAO_Encuesta($dbAdapter);
		$this->generador = new Encuesta_Util_Generator($dbAdapter);
		//$this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
    }

    public function indexAction()
    {
        // action body
    }

    public function acapaAction()
    {
        // action body
        $request = $this->getRequest();
		$idGrupo = $this->getParam("idGrupo");
		
		$grupo = $this->gruposDAO->obtenerGrupo($idGrupo);
		$capasGrupo = $this->evaluacionDAO->obtenerCapasEvaluacion($idGrupo);
		
        $formulario = new Encuesta_Form_AltaCapa;
		
		$this->view->formulario = $formulario;
		$this->view->grupo = $grupo;
		$this->view->capas = $capasGrupo;
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				
			}
		}
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $formulario = new Encuesta_Form_AltaEvaluacion;
		$this->view->formulario = $formulario;
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				
				
			}
		}
    }

    public function capasAction()
    {
        // action body
    }

    public function grupoAction()
    {
        // action body
        $idGrupo = $this->getParam("idGrupo");
		$asignaciones = $this->asignacionDAO->obtenerAsignacionesGrupo($idGrupo);
		
		$this->view->asignaciones = $asignaciones;
		$this->view->idGrupo = $idGrupo;
		$this->view->encuestaDAO = $this->encuestaDAO;
    }

    public function evaluarAction()
    {
        // action body
        $request = $this->getRequest();
        $idEncuesta = $this->getParam("idEncuesta");
		$idAsignacion = $this->getParam("idAsignacion");
		$generador = $this->generador;
		
		$asignacion = $this->gruposDAO->obtenerAsignacion($idAsignacion);
		
		$formulario = $generador->generarFormulario($idEncuesta, $idAsignacion);
		
		if($request->isGet()){
			$this->view->formulario = $formulario;
		}
		
		if ($request->isPost()) {
			$post = $request->getPost();
			//print_r($post);
			
			try{
				$generador->procesarFormulario($idEncuesta,$idAsignacion,$post);
				$this->view->messageSuccess = "Encuesta registrada correctamente";
			}catch(Exception $ex){
				$this->view->messageFail = "Error al Registrar la encuesta: " . $ex->getMessage();
			}
			/**/
		}
		
    }

    public function conjuntoAction()
    {
        // action body
    }

    public function evaluadoresAction()
    {
        // action body
    }


}





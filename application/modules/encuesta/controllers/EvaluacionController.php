<?php

class Encuesta_EvaluacionController extends Zend_Controller_Action
{
	private $asignacionDAO = null;
	private $gruposDAO = null;
	private $evaluacionDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo;
		$this->gruposDAO = new Encuesta_DAO_Grupos;
		$this->evaluacionDAO = new Encuesta_DAO_Evaluacion;
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
    }


}










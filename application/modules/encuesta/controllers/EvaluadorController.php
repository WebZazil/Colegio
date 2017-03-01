<?php

class Encuesta_EvaluadorController extends Zend_Controller_Action
{
	
	private $evaluacionDAO; 

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodquery");
        
        $this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        $evaluadores = $this->evaluacionDAO->getEvaluadoresByTipo("ALUM"); 
        $this->view->evaluadores = $evaluadores;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
		$formulario = new Encuesta_Form_AltaEvaluador;
		$this->view->formulario = $formulario;
		if ($request->isPost()) {
			if ($formulario->isValid($request->getPost())) {
				$datos = $formulario->getValues();
				
				$this->evaluacionDAO->addEvaluador($datos);
				
			}
		}
    }

    public function adminAction()
    {
        // action body
    }


}






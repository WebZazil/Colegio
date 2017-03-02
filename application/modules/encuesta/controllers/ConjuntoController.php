<?php

class Encuesta_ConjuntoController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodquery");
		
		$this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
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
        $idConjunto = $this->getParam("idConjunto");
		
		
		
    }


}










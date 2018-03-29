<?php

class Encuesta_EvaluadorController extends Zend_Controller_Action
{

    private $registroDAO = null;

    private $evaluacionDAO = null;
    private $evaluadorDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
        $this->evaluadorDAO = new Encuesta_Data_DAO_Evaluador($identity['adapter']);
        $this->evaluacionDAO = new Encuesta_DAO_Evaluacion($identity['adapter']);
        $this->registroDAO = new Encuesta_DAO_Registro($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $alumnas = $this->evaluacionDAO->getEvaluadoresByTipo("ALUM");
        $docentes = $this->evaluacionDAO->getEvaluadoresByTipo("DOCE");
        $evaluadores = array();
        
        foreach ($alumnas as $alumna){
            $evaluadores[] = $alumna;
        }
        
        foreach ($docentes as $docente) {
            $evaluadores[] = $docente;
        }
        
        $this->view->evaluadores = $evaluadores;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $estatusEvaluadores = $this->evaluadorDAO->getAllEstatusEvaluadores();
        $this->view->estatusEvaluadores = $estatusEvaluadores;
		
		if ($request->isPost()) {
		    $datos = $request->getPost();
		    $datos['creacion'] = date('Y-m-d H:i:s', time());
		    
		    try{
		        //$this->evaluacionDAO->addEvaluador($datos);
		        $this->evaluadorDAO->addEvaluador($datos);
		        $this->view->messageSuccess = "Evaluador: <strong>".$datos["apellidos"].", ".$datos["nombres"]."</strong> agregado correctamente.";
		    }catch(Exception $ex){
		        $this->view->messageFail = "Ha ocurrido un error: <strong>".$ex->getMessage()."</strong>";
		    }
		}
    }

    public function adminAction()
    {
        // action body
        $request = $this->getRequest();
        $idEvaluador = $this->getParam("ev");
		
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            
            $this->evaluacionDAO->editaEvaluador($idEvaluador, $datos);
            $this->_helper->redirector->gotoSimple("index", "evaluador", "encuesta");
        }
        
        $evaluacionDAO = $this->evaluacionDAO;
        $evaluador = $evaluacionDAO->getEvaluadorById($idEvaluador);
        
        $this->view->evaluador = $evaluador;
    }

    public function asociarAction() {
        // action body
        $idConjunto = $this->getParam("co");
		$idEvaluador = $this->getParam("ev");
		try{
			$this->evaluacionDAO->asociarEvaluadorAConjunto($idEvaluador, $idConjunto);
			$this->_helper->redirector->gotoSimple("evaluadores", "conjunto", "encuesta",array("co"=>$idConjunto));
		}catch(Exception $ex){
			print_r($ex->getMessage());
		}
    }

    public function normalizenAction() {
        // action body
        $this->evaluacionDAO->normalizarEvaluadores();
        $this->_helper->redirector->gotoSimple("index", "evaluador", "encuesta");
    }


}










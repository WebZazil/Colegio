<?php

class Encuesta_EvaluadorController extends Zend_Controller_Action
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
				try{
					$this->evaluacionDAO->addEvaluador($datos);
					$this->view->messageSuccess = "Evaluador: <strong>".$datos["apellidos"].", ".$datos["nombres"]."</strong> agregado correctamente.";
				}catch(Exception $ex){
					$this->view->messageFail = "Ha ocurrido un error: <strong>".$ex->getMessage()."</strong>";
				}
			}
		}
    }

    public function adminAction()
    {
        // action body
        $idEvaluador = $this->getParam("idEvaluador");
		$evaluacionDAO = $this->evaluacionDAO;
		$evaluador = $evaluacionDAO->getEvaluadorById($idEvaluador);
		
		$formulario = new Encuesta_Form_AltaEvaluador();
		$formulario->getElement("nombres")->setValue($evaluador["nombres"]);
		$formulario->getElement("apellidos")->setValue($evaluador["apellidos"]);
		$formulario->getElement("submit")->setLabel("Actualizar Evaluador")->setAttrib("class",	"btn btn-warning");
		
		$this->view->evaluador = $evaluador;
		$this->view->formulario = $formulario;
		
    }

    public function asociarAction()
    {
        // action body
        $idConjunto = $this->getParam("idConjunto");
		$idEvaluador = $this->getParam("idEvaluador");
		try{
			$this->evaluacionDAO->asociarEvaluadorAConjunto($idEvaluador, $idConjunto);
			$this->_helper->redirector->gotoSimple("evaluadores", "conjunto", "encuesta",array("idConjunto"=>$idConjunto));
		}catch(Exception $ex){
			print_r($ex->getMessage());
		}
    }


}








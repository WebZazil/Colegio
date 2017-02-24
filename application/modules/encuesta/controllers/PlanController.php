<?php

class Encuesta_PlanController extends Zend_Controller_Action
{
	private $planDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->planDAO = new Encuesta_DAO_Plan($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $planes = $this->planDAO->obtenerPlanesDeEstudio();
		$this->view->planes = $planes;
    }

    public function adminAction()
    {
        // action body
        $idPlan = $this->getParam("idPlan");
		$planDAO = $this->planDAO;
		$plan = $planDAO->obtenerPlanEstudios($idPlan);
		$this->view->plan = $plan;
        $formulario = new Encuesta_Form_AltaPlan;
		$formulario->getElement("planEducativo")->setValue($plan["planEducativo"]);
		$formulario->getElement("descripcion")->setValue($plan["descripcion"]);
		$formulario->getElement("vigente")->setValue($plan["vigente"]);
		$formulario->getElement("submit")->setLabel("Actualizar Plan");
		$formulario->getElement("submit")->setAttrib("class", "btn btn-warning");
		$this->view->formulario = $formulario;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        //$formulario = new Encuesta_Form_AltaPlan;
		//$this->view->formulario = $formulario;
		if($request->isPost()){
		    $datos = $request->getPost();
            if (array_key_exists("vigente", $datos)) $datos["vigente"] = 1;
            //print_r($datos);
            try{
                $this->planDAO->agregarPlanEstudios($datos);
                $this->view->messageSuccess = "Plan de estudios: <strong>".$datos["planEducativo"]."</strong> dado de alta exitosamente.";
            }catch(Exception $ex){
                $this->view->messageFail = $ex->getMessage();
            }
		}
    }

    public function editaAction()
    {
        // action body
        $idPlan = $this->getParam("idPlan");
        $datos = $this->getRequest()->getPost();
		unset($datos["submit"]);
		//$this->planDAO->agregarPlanEstudios($datos);
		print_r($datos);
		try{
			$this->planDAO->actualizarPlanEstudios($idPlan, $datos);
			$this->_helper->redirector->gotoSimple("admin", "plan", "encuesta",array("idPlan"=>$idPlan));
		}catch(Exception $ex){
			print_r($ex->getMessage());
		}
    }


}








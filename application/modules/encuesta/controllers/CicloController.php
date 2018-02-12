<?php

class Encuesta_CicloController extends Zend_Controller_Action
{
	private $cicloDAO = null;
    private $planDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        if (!$auth->hasIdentity()) {
            
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        
		$this->cicloDAO = new Encuesta_Data_DAO_CicloEscolar($identity['adapter']);
		$this->planDAO = new Encuesta_Data_DAO_PlanEducativo($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $idPlan = $this->getParam("idPlan");
        
        $ciclos = $this->cicloDAO->getAllCiclosEscolaresByIdPlanEducativo($idPlan);//->obtenerCiclos($idPlan);
        $plan = $this->planDAO->getPlanEducativoById($idPlan);
        
        $this->view->ciclos = $ciclos;
		$this->view->plan = $plan;
    }

    public function adminAction()
    {
        // action body
        $idCiclo = $this->getParam("idCiclo");
		$ciclo = $this->cicloDAO->getCicloEscolarById($idCiclo);//->obtenerCiclo($idCiclo);
		
		$formulario = new Encuesta_Form_AltaCiclo;
		$formulario->getElement("ciclo")->setValue($ciclo['ciclo']);
		$formulario->getElement("inicio")->setValue($ciclo['inicio']);
		$formulario->getElement("termino")->setValue($ciclo['termino']);
		$formulario->getElement("vigente")->setValue($ciclo['vigente']);
		$formulario->getElement("descripcion")->setValue($ciclo['descripcion']);
		$formulario->getElement("submit")->setLabel("Actualizar Ciclo");
		$formulario->getElement("submit")->setAttrib("class", "btn btn-warning");
		
		$this->view->ciclo = $ciclo;
		$this->view->formulario = $formulario;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $idPlan = $this->getParam("idPlan");
        
		$plan = $this->planDAO->getPlanEducativoById($idPlan);
        
		$this->view->plan = $plan;
		
		if($request->isPost()){
		    $datos = $request->getPost();
            if (array_key_exists("vigente", $datos)) $datos["vigente"] = 1;
            $inicio = new Zend_Date($datos["fechaInicio"], 'yyyy-MM-dd hh:mm:ss');
            $termino = new Zend_Date($datos["fechaTermino"], 'yyyy-MM-dd hh:mm:ss');
            $datos["inicio"] = $inicio->toString('yyyy-MM-dd hh:mm:ss');
            $datos["termino"] = $termino->toString('yyyy-MM-dd hh:mm:ss');
            $datos["idPlanEducativo"] = $idPlan;
            $datos["fecha"] = date("Y-m-d H:i:s", time());
            //print_r($datos);
            $ciclo = new Encuesta_Models_Ciclo($datos);
            try{
                $this->cicloDAO->addCiclo($ciclo);//crearCiclo($datos);
                $this->view->messageSuccess = "Ciclo Escolar: <strong>".$datos["ciclo"]. "</strong> dato de alta correctamente." ;
            }catch(Exception $ex){
                $this->view->messageFail = $ex->getMessage();
            }
		}
    }

    public function editaAction()
    {
        // action body
        $idCiclo = $this->getParam("idCiclo");
        $request = $this->getRequest();
		$datos = $request->getPost();
		unset($datos["submit"]);
		try{
			$this->cicloDAO->editCiclo($idCiclo, $datos);
			$this->_helper->redirector->gotoSimple("admin", "ciclo", "encuesta",array("idCiclo"=>$idCiclo));
		}catch(Exception $ex){
			print_r($ex->getMessage());
		}
    }

    public function ciclosAction()
    {
        // action body
        $idPlan = $this->getParam("idPlan");
        
        $ciclos = $this->cicloDAO->getAllCiclosEscolaresByIdPlanEducativo($idPlan);
        $plan = $this->planDAO->getPlanEducativoById($idPlan);
        
        $this->view->ciclos = $ciclos;
        $this->view->plan = $plan;
    }

}

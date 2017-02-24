<?php

class Encuesta_CicloController extends Zend_Controller_Action
{
	private $cicloDAO = null;
    private $planDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
		$this->planDAO = new Encuesta_DAO_Plan($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $idPlan = $this->getParam("idPlan");
        $ciclos = $this->cicloDAO->getCiclosbyIdPlan($idPlan);//->obtenerCiclos($idPlan);
		$plan = $this->planDAO->obtenerPlanEstudios($idPlan);
        $this->view->ciclos = $ciclos;
		$this->view->plan = $plan;
    }

    public function adminAction()
    {
        // action body
        $idCiclo = $this->getParam("idCiclo");
		$ciclo = $this->cicloDAO->getCicloById($idCiclo);//->obtenerCiclo($idCiclo);
		
		$formulario = new Encuesta_Form_AltaCiclo;
		$formulario->getElement("ciclo")->setValue($ciclo->getCiclo());
		$formulario->getElement("inicio")->setValue($ciclo->getInicio());
		$formulario->getElement("termino")->setValue($ciclo->getTermino());
		$formulario->getElement("vigente")->setValue($ciclo->getVigente());
		$formulario->getElement("descripcion")->setValue($ciclo->getDescripcion());
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
		$plan = $this->planDAO->obtenerPlanEstudios($idPlan);
        //$formulario = new Encuesta_Form_AltaCiclo;
		//$this->view->formulario = $formulario;
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
            }catch(Util_Exception_BussinessException $ex){
                $this->view->messageFail = $ex->getMessage();
            }
            /*
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				
				$inicio = new Zend_Date($datos["inicio"], 'yyyy-MM-dd hh-mm-ss');
				$termino = new Zend_Date($datos["termino"], 'yyyy-MM-dd hh-mm-ss');
				
				$datos["inicio"] = $inicio->toString('yyyy-MM-dd hh-mm-ss');
				$datos["termino"] = $termino->toString('yyyy-MM-dd hh-mm-ss');
				$datos["idPlanEducativo"] = $idPlan;
				
				try{
					$this->cicloDAO->crearCiclo($datos);
					$this->view->messageSuccess = "Ciclo Escolar: <strong>".$datos["ciclo"]. "</strong> dato de alta efectivamente." ;
				}catch(Util_Exception_BussinessException $ex){
					$this->view->messageFail = $ex->getMessage();
				}
				
			}
            */
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
        $ciclos = $this->cicloDAO->getCiclosbyIdPlan($idPlan);//->obtenerCiclos($idPlan);
        $plan = $this->planDAO->obtenerPlanEstudios($idPlan);
        $this->view->ciclos = $ciclos;
        $this->view->plan = $plan;
    }


}










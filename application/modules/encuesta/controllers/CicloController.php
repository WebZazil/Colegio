<?php

class Encuesta_CicloController extends Zend_Controller_Action
{
	private $cicloDAO = null;
    private $planDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
		$this->cicloDAO = new Encuesta_Data_DAO_CicloEscolar($identity['adapter']);
		$this->planDAO = new Encuesta_Data_DAO_PlanEducativo($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $idPlan = $this->getParam("pl");
        
        $ciclos = $this->cicloDAO->getAllCiclosEscolaresByIdPlanEducativo($idPlan);//->obtenerCiclos($idPlan);
        $plan = $this->planDAO->getPlanEducativoById($idPlan);
        
        $this->view->ciclos = $ciclos;
		$this->view->plan = $plan;
    }

    public function adminAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idCiclo = $this->getParam("cl");
		
		if($request->isPost()){
		    $datos = $request->getPost();
		    if (array_key_exists("vigente", $datos)) $datos["vigente"] = 1;
		    
		    $fInicio = new DateTime($datos['inicio']);
		    $fTermino = new DateTime($datos['termino']);
		    
		    $datos['inicio'] = $fInicio->format('Y-m-d H:i:s');
		    $datos['termino'] = $fTermino->format('Y-m-d H:i:s');
		    
		    try {
		        //$this->cicloDAO->editCiclo($idCiclo, $datos);
		        $this->cicloDAO->updateCicloEscolar($idCiclo, $datos);
		    } catch (Exception $e) {
		        print_r($e->getMessage());
		    }
		}
		
		$ciclo = $this->cicloDAO->getCicloEscolarById($idCiclo);
		
		$plan = $this->planDAO->getPlanEducativoById($ciclo['idPlanEducativo']);
		
		$this->view->ciclo = $ciclo;
		$this->view->plan = $plan;
		
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idPlan = $this->getParam("pl");
        $plan = $this->planDAO->getPlanEducativoById($idPlan);
        
		$this->view->plan = $plan;
		
		if($request->isPost()){
		    $datos = $request->getPost();
            if (array_key_exists("vigente", $datos)) $datos["vigente"] = 1;
            $inicio = new Zend_Date($datos["inicio"], 'yyyy-MM-dd hh:mm:ss');
            $termino = new Zend_Date($datos["termino"], 'yyyy-MM-dd hh:mm:ss');
            $datos["inicio"] = $inicio->toString('yyyy-MM-dd hh:mm:ss');
            $datos["termino"] = $termino->toString('yyyy-MM-dd hh:mm:ss');
            $datos["idPlanEducativo"] = $idPlan;
            $datos["creacion"] = date("Y-m-d H:i:s", time());
            
            try{
                $idCiclo = $this->cicloDAO->addCicloEscolar($datos);
                $this->cicloDAO->actualizarCiclosACicloVigente($idCiclo);
                $this->view->messageSuccess = "Ciclo Escolar: <strong>".$datos["ciclo"]. "</strong> dato de alta correctamente." ;
            }catch(Exception $ex){
                $this->view->messageFail = $ex->getMessage();
            }
		}
    }

    public function editaAction()
    {
        // action body @TODO Deprecated
    }

    public function ciclosAction()
    {
        // action body
        $idPlan = $this->getParam("pl");
        
        $ciclos = $this->cicloDAO->getAllCiclosEscolaresByIdPlanEducativo($idPlan);
        $plan = $this->planDAO->getPlanEducativoById($idPlan);
        
        $this->view->ciclos = $ciclos;
        $this->view->plan = $plan;
    }

}

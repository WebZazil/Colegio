<?php

class Encuesta_ConsultaController extends Zend_Controller_Action
{
	private $nivelDAO = null;
	private $cicloDAO = null;
	private $planDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->nivelDAO = new Encuesta_DAO_Nivel;
		$this->cicloDAO = new Encuesta_DAO_Ciclo;
		$this->planDAO = new Encuesta_DAO_Plan;
    }

    public function indexAction()
    {
        // action body
        $planActual = $this->planDAO->obtenerPlanEstudiosVigente();
        $niveles = $this->nivelDAO->obtenerNiveles();
		$ciclosEscolares = $this->cicloDAO->getCiclosbyIdPlan($planActual["idPlanEducativo"]);//->obtenerCiclos($planActual["idPlanEducativo"]);
		$this->view->niveles = $niveles;
		$this->view->ciclosEscolares = $ciclosEscolares;
    }


}


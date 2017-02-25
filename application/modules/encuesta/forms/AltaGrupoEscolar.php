<?php

class Encuesta_Form_AltaGrupoEscolar extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $planDAO = new Encuesta_DAO_Plan($dataIdentity["adapter"]);
		$plan = $planDAO->obtenerPlanEstudiosVigente();
		
        $cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
		//$ciclos = $cicloDAO->obtenerCiclos($plan["idPlanEducativo"]);
		$ciclos = $cicloDAO->getCiclosbyIdPlan($plan["idPlanEducativo"]);
		$ciclo = $cicloDAO->getCurrentCiclo();
        
        $eCiclo = new Zend_Form_Element_Select("idCicloEscolar");
        $eCiclo->setLabel("Ciclo: ");
		$eCiclo->setAttrib("class", "form-control");
		$eCiclo->addMultiOption($ciclo->getIdCiclo(),$ciclo->getCiclo());
		/*
		foreach ($ciclos as $ciclo) {
			$eCiclo->addMultiOption($ciclo->getIdCiclo(),$ciclo->getCiclo());
		}*/
		
		$eGrado = new Zend_Form_Element_Select("idGradoEducativo");
		$eGrado->setLabel("Grado: ");
		$eGrado->setAttrib("class", "form-control");
        
        $eGrupo = new Zend_Form_Element_Text("grupoEscolar");
        $eGrupo->setLabel("Grupo: ");
		$eGrupo->setAttrib("class", "form-control");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Agregar Grupo");
        $eSubmit->setAttrib("class", "btn btn-success");
        
		
		$this->addElements(array($eCiclo,$eGrado,$eGrupo,$eSubmit));
    }


}


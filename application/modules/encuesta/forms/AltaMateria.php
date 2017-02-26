<?php

class Encuesta_Form_AltaMateria extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
		
        $cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
		//$ciclo = $cicloDAO->obtenerCicloActual();
		$ciclo = $cicloDAO->getCurrentCiclo();
		
        $nivelDAO = new Encuesta_DAO_Nivel($dataIdentity["adapter"]);
		$niveles = $nivelDAO->obtenerNiveles();
		
		$gradoDAO = new Encuesta_DAO_Grado($dataIdentity["adapter"]);
		//$grados = $gradoDAO->obtenerGrados("1");
		$grados = $gradoDAO->getGradoById($ciclo->getIdCiclo());
		
		$eCiclo = new Zend_Form_Element_Select("idCicloEscolar");
		$eCiclo->setLabel("Ciclo Escolar:");
		$eCiclo->setAttrib("class", "form-control");
		$eCiclo->addMultiOption($ciclo->getIdCiclo(),$ciclo->getCiclo());
		
		$eNivel = new Zend_Form_Element_Select("idNivelEducativo");
		$eNivel->setLabel("Nivel: ");
		$eNivel->setAttrib("class", "form-control");
		
		foreach ($niveles as $nivel) {
			$eNivel->addMultiOption($nivel->getIdNivel(),$nivel->getNivel());
		}
		
		$eGrado = new Zend_Form_Element_Select("idGradoEducativo");
		$eGrado->setAttrib("class", "form-control");
		$eGrado->setLabel("Grado: ");
		$eGrado->setRegisterInArrayValidator(false);
		if(!empty($grados)){
			foreach ($grados as $grado) {
				$eGrado->addMultiOption($grado->getIdGrado(),$grado->getGrado());
			}
		}
		
        $eMateria = new Zend_Form_Element_Text("materiaEscolar");
		$eMateria->setLabel("Materia: ");
        $eMateria->setAttrib("class", "form-control");
		
		$eCreditos = new Zend_Form_Element_Text("creditos");
		$eCreditos->setLabel("Creditos: ");
		$eCreditos->setValue(1);
		$eCreditos->setAttrib("class", "form-control");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Agregar Materia");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		
		$this->addElements(array($eCiclo,$eNivel,$eGrado,$eMateria,$eCreditos,$eSubmit));
    }


}


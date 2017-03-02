<?php

class Encuesta_Form_AltaEvaluador extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $tiposEvaluador = Zend_Registry::get("tiposEvaluador");
        
        $eNombres = new Zend_Form_Element_Text("nombres");
		$eNombres->setLabel("Nombres: ");
		$eNombres->setAttrib("class", "form-control");
		$eNombres->setAttrib("placeholder", "Nombres...");
		$eNombres->setAttrib("tipo", "nombres");
		//$eNombres->setAttrib("autofocus", "");
		$eNombres->setRequired(true);
		
		$eApellidos = new Zend_Form_Element_Text("apellidos");
		$eApellidos->setLabel("Apellidos: ");
		$eApellidos->setAttrib("class", "form-control");
		$eApellidos->setAttrib("placeholder", "Apellidos...");
		$eApellidos->setAttrib("autofocus", "");
		$eApellidos->setAttrib("tipo", "apellidos");
		$eApellidos->setRequired(true);
		
		$eTipo = new Zend_Form_Element_Select("tipo");
		$eTipo->setLabel("Tipos Evaluador: ");
		$eTipo->setAttrib("class", "form-control");
		foreach ($tiposEvaluador as $index => $value) {
			$eTipo->addMultiOption($index,$value);
		}
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Agregar Evaluador");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		$this->addElements(array($eApellidos,$eNombres,$eTipo,$eSubmit));
    }


}


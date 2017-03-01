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
		$eNombres->setRequired(true);
		
		$eApellidos = new Zend_Form_Element_Text("apellidos");
		$eApellidos->setLabel("Apellidos: ");
		$eApellidos->setAttrib("class", "form-control");
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
		
		$this->addElements(array($eNombres,$eApellidos,$eTipo,$eSubmit));
    }


}


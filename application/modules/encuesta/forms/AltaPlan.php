<?php

class Encuesta_Form_AltaPlan extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setAttrib("class", "form-horizontal");
		
        $ePlan = new Zend_Form_Element_Text("planEducativo");
		$ePlan->setLabel("Plan de Estudios: ");
		$ePlan->setAttrib("class", "form-control");
		$ePlan->setAttrib("minlength", "5");
		$ePlan->setAttrib("maxlength", "15");
		$ePlan->setAttrib("placeholder", "Ejem: 2015-2018");
        
        $eDescripcion = new Zend_Form_Element_Textarea("descripcion");
		$eDescripcion->setLabel("Descripcion: ");
		$eDescripcion->setAttrib("class", "form-control");
		$eDescripcion->setAttrib("rows", "2");
		$eDescripcion->setAttrib("placeholder", "Agregue características a este plan de estudios...");
		
		$eVigente = new Zend_Form_Element_Checkbox("vigente");
		$eVigente->setLabel("Esta Vigente?:");
		//$eVigente->setAttrib("class", "form-control");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Agregar Plan de Estudios");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		$inputStandarBootstrapDecorators = array(
			'ViewHelper',	// Elemento
			'Errors',		// Errores
			array(array('data'=>'HtmlTag'), array('tag'=>'div', "class"=>"col-xs-8")),
			array('Label', array('class' => 'col-xs-4')),
			array(array('element'=>'HtmlTag'), array('tag'=>'div', 'class' => 'form-group'))
		);
		
		$buttonStandarBootstrapDecorators = array(
			'ViewHelper',	// Elemento
			array(array('data'=>'HtmlTag'), array('tag'=>'div', "class"=>"col-xs-12")),
			//'Label',
			array(array('element'=>'HtmlTag'), array('tag'=>'div', 'class' => 'form-group'))
		);
		
		$ePlan->setDecorators($inputStandarBootstrapDecorators);
		$eDescripcion->setDecorators($inputStandarBootstrapDecorators);
		$eVigente->setDecorators($inputStandarBootstrapDecorators);
		$eSubmit->setDecorators($buttonStandarBootstrapDecorators);
		
		$this->addElements(array($ePlan,$eDescripcion,$eVigente,$eSubmit));
    }


}


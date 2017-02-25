<?php

class Encuesta_Form_AltaGrado extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setAttrib("class", "form-horizontal");
        
        $grados = Zend_Registry::get("gradosEscolares");
        
        $abreviaturas = array(
			"1" => "1°",
			"2" => "2°",
			"3" => "3°",
			"4" => "4°",
			"5" => "5°",
			"6" => "6°",
			"7" => "7°",
			"8" => "8°",
			"9" => "9°",
			"10" => "10°"
		);
        
        $eGrado = new Zend_Form_Element_Text("gradoEducativo");
		$eGrado->setLabel("Grado: ");
        $eGrado->setAttrib("class", "form-control");
		$eGrado->setAttrib("placeholder", "Ej: Primero, Segundo, Primer Semestre, etc...");
		
		$eAbreviatura = new Zend_Form_Element_Select("abreviatura");
		$eAbreviatura->setLabel("Abreviatura: ");
		$eAbreviatura->setMultiOptions($grados);
		$eAbreviatura->setAttrib("class", "form-control");
		
		$eDescripcion = new Zend_Form_Element_Text("descripcion");
		$eDescripcion->setLabel("Descripción (opcional): ");
		$eDescripcion->setAttrib("class", "form-control");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Agregar Grado");
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
		
		$eGrado->setDecorators($inputStandarBootstrapDecorators);
		$eAbreviatura->setDecorators($inputStandarBootstrapDecorators);
		$eDescripcion->setDecorators($inputStandarBootstrapDecorators);
		$eSubmit->setDecorators($buttonStandarBootstrapDecorators);
		
		$this->addElements(array($eGrado,$eAbreviatura,$eDescripcion,$eSubmit));
    }


}


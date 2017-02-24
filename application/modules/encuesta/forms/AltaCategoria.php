<?php

class Encuesta_Form_AltaCategoria extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setAttrib("class", "form-horizontal");
		
        $eCategoria = new Zend_Form_Element_Text("categoria");
		$eCategoria->setLabel("Categoria");
		$eCategoria->setAttrib("class", "form-control");
		$eCategoria->setAttrib("required", "required");
		$eCategoria->setAttrib("autofocus", "");
		$eCategoria->setAttrib("placeholder", "Ejem: Dias de la semana, Frutas, etc...");
		
		$eDescripcion = new Zend_Form_Element_Textarea("descripcion");
		$eDescripcion->setLabel("Agregue una descripcion de esta categoria");
		$eDescripcion->setAttrib("class", "form-control");
		$eDescripcion->setAttrib("required", "required");
		$eDescripcion->setAttrib("rows", "2");
		$eDescripcion->setAttrib("placeholder", "Una breve descripcion acerca de esta categoria...");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar Categoria");
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
		
		$eCategoria->setDecorators($inputStandarBootstrapDecorators);
		$eDescripcion->setDecorators($inputStandarBootstrapDecorators);
		$eSubmit->setDecorators($buttonStandarBootstrapDecorators);
		
		$this->addElements(array($eCategoria, $eDescripcion, $eSubmit));
    }


}


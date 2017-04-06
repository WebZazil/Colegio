<?php

class Encuesta_Form_AltaPregunta extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setAttrib("class", "form-horizontal");
        $tipo = Zend_Registry::get("tipo");
        
        //$eId = new Zend_Form_Element_Hidden("idOrigen");
        
        $ePregunta = new Zend_Form_Element_Text("nombre");
        $ePregunta->setLabel("Pregunta:");
        $ePregunta->setAttrib("class", "form-control");
        
        $eTipo = new Zend_Form_Element_Select("tipo");
        $eTipo->setLabel("Tipo de pregunta:");
        $eTipo->setAttrib("class", "form-control");
        $eTipo->addMultiOptions($tipo);
        //$eTipo->setMultiOptions($tipo);
        //$eTipo->clearMultiOptions();
        //$eTipo->addMultiOption($option);
        
        $eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Crear pregunta");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $inputStandarBootstrapDecorators = array(
            'ViewHelper',   // Elemento
            'Errors',       // Errores
            array(array('data'=>'HtmlTag'), array('tag'=>'div', "class"=>"col-xs-8")),
            array('Label', array('class' => 'col-xs-4')),
            array(array('element'=>'HtmlTag'), array('tag'=>'div', 'class' => 'form-group'))
        );
        
        $buttonStandarBootstrapDecorators = array(
            'ViewHelper',   // Elemento
            array(array('data'=>'HtmlTag'), array('tag'=>'div', "class"=>"col-xs-12")),
            //'Label',
            array(array('element'=>'HtmlTag'), array('tag'=>'div', 'class' => 'form-group'))
        );
        
        $ePregunta->setDecorators($inputStandarBootstrapDecorators);
        $eTipo->setDecorators($inputStandarBootstrapDecorators);
        $eSubmit->setDecorators($buttonStandarBootstrapDecorators);
        
        //$this->addElement($eId);
        $this->addElement($ePregunta);
        $this->addElement($eTipo);
        $this->addElement($eSubmit);
    }


}


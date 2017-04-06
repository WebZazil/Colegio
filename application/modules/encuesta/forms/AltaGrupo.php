<?php

class Encuesta_Form_AltaGrupo extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setAttrib("class", "form-horizontal");
        $tipos = Zend_Registry::get("tipo");
        $eNombre = new Zend_Form_Element_Text("nombre");
        $eNombre->setLabel("Nombre del Grupo: ");
        $eNombre->setAttrib("class", "form-control");
        $eNombre->setAttrib("placeholder", "Nombre del contenedor Grupo...");
        $eNombre->setAttrib("required", "required");
        $eNombre->setAttrib("autofocus", "");
        
        $eTipo = new Zend_Form_Element_Select("tipo");
        $eTipo->setLabel("Tipo del grupo: ");
        $eTipo->setAttrib("class", "form-control");
        $eTipo->addMultiOptions($tipos);
        
        $eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Agregar Grupo");
        //$eTipo->setAttrib("class", "btn btn-primary");
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
        
        $eNombre->setDecorators($inputStandarBootstrapDecorators);
        $eTipo->setDecorators($inputStandarBootstrapDecorators);
        $eSubmit->setDecorators($buttonStandarBootstrapDecorators);
        
        $this->addElement($eNombre);
        $this->addElement($eTipo);
        $this->addElement($eSubmit);
    }


}


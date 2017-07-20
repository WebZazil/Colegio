<?php

class Biblioteca_Form_AltaAutor extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $tipoAutores = array("UN"=>"Unico","VR"=>"Varios","IN"=>"Indefinido",);
        
        $eTipoAutor = new Zend_Form_Element_Select("tipo");
        $eTipoAutor->setLabel("Tipo Autor: ");
        $eTipoAutor->setAttrib("class", "form-control");
        $eTipoAutor->setMultiOptions($tipoAutores);
        
        $eNombres = new Zend_Form_Element_Text("nombres");
        $eNombres->setLabel("Nombres: ");
        $eNombres->setAttrib("placeholder", "Nombres del Autor...");
        $eNombres->setAttrib("class", "form-control");
        
        $eApellidos = new Zend_Form_Element_Text("apellidos");
        $eApellidos->setLabel("Apellidos: ");
        $eApellidos->setAttrib("class", "form-control");
        $eApellidos->setAttrib("placeholder", "Apellidos del Autor...");
        
        $eAutores = new Zend_Form_Element_Text("autores");
        $eAutores->setLabel("Autores: ");
        $eAutores->setAttrib("class", "form-control");
        $eAutores->setAttrib("placeholder", "Autores...");
        
        $eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Guardar");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $this->addElements(array($eTipoAutor,$eNombres,$eApellidos,$eAutores, $eSubmit));
    }


}


<?php

class Biblioteca_Form_AltaIdioma extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $eIdioma = new Zend_Form_Element_Text("idioma");
		$eIdioma->setLabel("Idioma:");
		$eIdioma->setAttrib("class", "form-control");
		$eIdioma->setAttrib("required", "required");
		
		$eEnglish = new Zend_Form_Element_Text("english");
		$eEnglish->setLabel("Idioma (inglés)");
		$eEnglish->setAttrib("class", "form-control");
		$eEnglish->setAttrib("required", "required");
		
		$eCodigo = new Zend_Form_Element_Text("codigo");
		$eCodigo->setLabel("Codigo");
		$eCodigo->setAttrib("class", "form-control");
		$eCodigo->setAttrib("required", "required");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Guardar");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $this->addElements(array($eIdioma, $eEnglish,$eCodigo, $eSubmit));
		
		
    }


}


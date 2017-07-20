<?php

class Biblioteca_Form_AltaTema extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $eTema = new Zend_Form_Element_Text("tema");
		$eTema->setLabel("Tema: ");
		$eTema->setAttrib("class", "form-control");
		$eTema->setAttrib("required", "requiered");
        
        $eCodigo = new Zend_Form_Element_Text("codigo");
		$eCodigo->setLabel("Código: ");
		$eCodigo->setAttrib("class", "form-control");
		$eCodigo->setAttrib("required", "requiered");
		
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Guardar");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $this->addElements(array($eTema,$eCodigo, $eSubmit));
    }


}


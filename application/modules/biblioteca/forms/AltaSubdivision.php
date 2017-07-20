<?php

class Biblioteca_Form_AltaSubdivision extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $eSubdivision = new Zend_Form_Element_Text("subdivision");
		$eSubdivision->setLabel("Nombre de Subdivisión:");
		$eSubdivision->setAttrib("class", "form-control");
		$eSubdivision->setAttrib("required", "required");
		
		$eCodigo = new Zend_Form_Element_Text("codigo");
		$eCodigo->setLabel("Código");
		$eCodigo->setAttrib("class", "form-control");
		$eSubdivision->setAttrib("required", "required");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Guardar");
        $eSubmit->setAttrib("class", "btn btn-success");
		$eSubdivision->setAttrib("required", "required");
        
        $this->addElements(array($eSubdivision, $eCodigo,$eSubmit));
		
		
    }


}


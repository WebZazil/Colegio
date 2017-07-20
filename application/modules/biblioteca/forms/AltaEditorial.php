<?php

class Biblioteca_Form_AltaEditorial extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $eEditorial = new Zend_Form_Element_Text("editorial");
		$eEditorial->setLabel("Nombre de Editorial:");
		$eEditorial->setAttrib("class", "form-control");
		$eEditorial->setAttrib("required", "required");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Guardar");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $this->addElements(array($eEditorial, $eSubmit));
    }


}


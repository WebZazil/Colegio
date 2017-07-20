<?php

class Biblioteca_Form_AltaColeccion extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $eColeccion = new Zend_Form_Element_Text("coleccion");
		$eColeccion->setLabel("Colección: ");
		$eColeccion->setAttrib("class", "form-control");
		$eColeccion->setAttrib("required", "requiered");
        
        $eCodigo = new Zend_Form_Element_Text("codigo");
		$eCodigo->setLabel("Código: ");
		$eCodigo->setAttrib("class", "form-control");
		$eCodigo->setAttrib("required", "requiered");
		
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Guardar");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $this->addElements(array($eColeccion,$eCodigo, $eSubmit));
		
    }


}


<?php

class Biblioteca_Form_AltaClasificacion extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $eClasificacion = new Zend_Form_Element_Text("clasificacion");
		$eClasificacion->setLabel("Clasificación: ");
		$eClasificacion->setAttrib("class", "form-control");
		$eClasificacion->setAttrib("required", "requiered");
		
		$eDescripcion = new Zend_Form_Element_Text("descripcion");
		$eDescripcion->setLabel("Descripción: ");
		$eDescripcion->setAttrib("class","form-control");
		$eDescripcion->setAttrib("required","required");
		
		$eCodigo = new Zend_Form_Element_Text("codigo");
		$eCodigo->setLabel("Código:");
		$eCodigo->setAttrib("class","form-control");
		$eCodigo->setAttrib("required", "required");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class","btn btn-success");
		
		$this->addElements(array($eClasificacion,$eDescripcion,$eCodigo,$eSubmit));
    }


}


<?php

class Biblioteca_Form_AltaMateria extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setAttrib("id", "altaMateria");
		
		$eMateria = new Zend_Form_Element_Text("materia");
		$eMateria->setLabel("Materia");
		$eMateria->setAttrib("class", "form-control");
		$eMateria->setAttrib("required", "required");
		
		$eDescripcion = new Zend_Form_Element_Text("descripcion");
		$eDescripcion->setLabel("Descripción");
		$eDescripcion->setAttrib("class","form-control");
		//$eDescripcion->setAttrib("required", "required");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar Materia");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		$this->addElement($eMateria);
		$this->addElement($eDescripcion);
		
		$this->addElement($eSubmit);
    }


}


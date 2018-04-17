<?php

class Biblioteca_Form_AltaPais extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $ePais = new Zend_Form_Element_Text("pais");
        $ePais->setLabel("Pais: ");
        $ePais->setAttrib("class", "form-control");
        $ePais->setAttrib("required", "requiered");
        
        $eIngles = new Zend_Form_Element_Text("english");
        $eIngles->setLabel("Inglés: ");
        $eIngles->setAttrib("class", "form-control");
        $eIngles->setAttrib("required", "requiered");
        
        $eCodigo = new Zend_Form_Element_Text("codigo");
        $eCodigo->setLabel("Código: ");
        $eCodigo->setAttrib("class", "form-control");
        $eCodigo->setAttrib("required", "requiered");
        
        
        $eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Guardar");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $this->addElements(array($ePais,$eIngles,$eCodigo, $eSubmit));
    }


}


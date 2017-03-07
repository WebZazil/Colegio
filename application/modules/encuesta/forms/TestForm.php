<?php

class Encuesta_Form_TestForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $eTextOne = new Zend_Form_Element_Text("textOne");
        $eTextOne->setLabel("Campo de texto uno: ");
        $eTextOne->setAttrib("class", "form-control");
        
        $eTextTwo = new Zend_Form_Element_Text("textTwo");
        $eTextTwo->setLabel("Campo de texto dos: ");
        $eTextTwo->setAttrib("class", "form-control");
        
        $options = array("One","Two","Three","Four","Five","Six","Seven");
        
        $eSelectOne = new Zend_Form_Element_Select("selectOne");
        $eSelectOne->setLabel("Combo de seleccion uno");
        $eSelectOne->setAttrib("class", "form-control");
        $eSelectOne->setMultiOptions($options);
        
        $eSelectTwo = new Zend_Form_Element_Select("selectTwo");
        $eSelectTwo->setLabel("Combo de seleccion dos");
        $eSelectTwo->setAttrib("class", "form-control");
        $eSelectTwo->setMultiOptions($options);
        
        $sOne = new Zend_Form_SubForm("subOne");
        $sOne->addElements(array($eTextOne,$eSelectOne));
        
        $sTwo = new Zend_Form_SubForm("subTwo");
        $sTwo->addElements(array($eTextTwo,$eSelectTwo));
        
        $this->addSubForms(array($sOne,$sTwo)); 
        
    }


}


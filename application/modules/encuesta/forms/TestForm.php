<?php

class Encuesta_Form_TestForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $elementDecorators = Zend_Registry::get("zfed");
        
        $eTextOne = new Zend_Form_Element_Text("textOne");
        $eTextOne->setLabel("Campo de texto uno: ");
        $eTextOne->setAttrib("class", "form-control");
        
        $eTextTwo = new Zend_Form_Element_Text("textTwo");
        $eTextTwo->setLabel("Campo de texto dos: ");
        $eTextTwo->setAttrib("class", "form-control");
        
        $options = array("One","Two","Three","Four","Five","Six","Seven");
        
        $eSelectOne = new Zend_Form_Element_Select("selectOne");
        $eSelectOne->setLabel("Combo uno");
        $eSelectOne->setAttrib("class", "form-control");
        $eSelectOne->setMultiOptions($options);
        
        $eSelectTwo = new Zend_Form_Element_Select("selectTwo");
        $eSelectTwo->setLabel("Combo dos");
        $eSelectTwo->setAttrib("class", "form-control");
        $eSelectTwo->setMultiOptions($options);
		
		$eSelectThree = new Zend_Form_Element_Select("selectThree");
        $eSelectThree->setLabel("Combo tres");
        $eSelectThree->setAttrib("class", "form-control");
        $eSelectThree->setMultiOptions($options);
		
		$eSelectFour = new Zend_Form_Element_Select("selectFour");
        $eSelectFour->setLabel("Combo cuatro");
        $eSelectFour->setAttrib("class", "form-control");
        $eSelectFour->setMultiOptions($options);
		
		$eSelectFive = new Zend_Form_Element_Select("selectFive");
        $eSelectFive->setLabel("Combo cinco");
        $eSelectFive->setAttrib("class", "form-control");
        $eSelectFive->setMultiOptions($options);
		
		$eSelectSix = new Zend_Form_Element_Select("selectSix");
        $eSelectSix->setLabel("Combo seis");
        $eSelectSix->setAttrib("class", "form-control");
        $eSelectSix->setMultiOptions($options);
        
        $sOne = new Zend_Form_SubForm("subOne");
		$sOne->setLegend("SubForm Uno");
        $sOne->addElements(array($eTextOne,$eTextTwo,$eSelectOne,$eSelectTwo));
		$sOne->setElementDecorators($elementDecorators);
        
        $sTwo = new Zend_Form_SubForm("subTwo");
		$sTwo->setLegend("SubForm Dos");
        $sTwo->addElements(array($eSelectThree,$eSelectFour,$eSelectFive,$eSelectSix));
		$sTwo->setElementDecorators($elementDecorators);
        
        $this->addSubForms(array($sOne,$sTwo)); 
        
        
        
    }


}


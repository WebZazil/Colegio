<?php

class Biblioteca_Form_AltaTemasSubdivision extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $this->setAttrib("id", "altaTemaSubdivision");
        
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
		
        $temaDAO = new Biblioteca_Data_DAO_Tema($identity['adapter']);
		$temas = $temaDAO->getAllTemas();
		
		$eTema = new Zend_Form_Element_Select("idTema");
		$eTema->setLabel("Selecciona Tema");
		$eTema->setAttrib("class", "form-control");
		$eTema->setRegisterInArrayValidator(FALSE);
		
		foreach ($temas as $tema) {
			$eTema->addMultiOption($tema["idTema"], $tema["tema"]);
		}
		
		$subdivisionDAO = new Biblioteca_Data_DAO_Subdivision($identity['adapter']);
		$subdivisiones = $subdivisionDAO->getAllSubdivisiones();
		
		$eSubdivision = new Zend_Form_Element_Select("idSubdivision");
		$eSubdivision->setLabel("Selecciona subdivision");
		$eSubdivision->setAttrib("class", "form-control");
		$eSubdivision->setRegisterInArrayValidator(FALSE);
		
		foreach ($subdivisiones as $subdivision) {
			$eSubdivision->addMultiOption($subdivision["idSubdivision"], $subdivision["subdivision"]);
		}
		
		$eSubmit =  new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn-btn-success");
		
		$this->addElement($eTema);
		$this->addElement($eSubdivision);
		$this->addElement($eSubmit);
    }

}





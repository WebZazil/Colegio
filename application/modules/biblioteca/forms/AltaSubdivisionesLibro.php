<?php

class Biblioteca_Form_AltaSubdivisionesLibro extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
               
        $this->setAttrib("id", "altaSubdivisionesLibro");
        
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
		
        $subdivisionDAO = new Biblioteca_Data_DAO_Subdivision($identity['adapter']);
		$subdivisiones = $subdivisionDAO->getAllSubdivisiones();
		
		$eSubdivision = new Zend_Form_Element_Select("idSubdivision");
		$eSubdivision->setLabel("Selecciona subdivisión");
		$eSubdivision->setAttrib("class", "form-control");
		$eSubdivision->setRegisterInArrayValidator(FALSE);
		
		foreach ($subdivisiones as $subdivision) {
			$eSubdivision->addMultiOption($subdivision["idSubdivision"], $subdivision["subdivision"]);
		}
		
		$recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
		$recursos = $recursoDAO->getAllTableRecursos();
		
		$eRecurso = new Zend_Form_Element_Select("idRecurso");
		$eRecurso->setLabel("Selecciona un recurso");
		$eRecurso->setAttrib("class", "form-control");
		$eRecurso->setRegisterInArrayValidator(FALSE);
		
		foreach($recursos as $recurso){
		    $eRecurso->addMultiOption($recurso['idRecurso'], $recurso['titulo']);
		}
		
		$eSubmit =  new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn-btn-success");
		
		$this->addElement($eSubdivision);
		$this->addElement($eRecurso);
		$this->addElement($eSubmit);
    }

}


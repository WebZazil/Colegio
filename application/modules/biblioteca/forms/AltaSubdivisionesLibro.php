<?php

class Biblioteca_Form_AltaSubdivisionesLibro extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
               
        $this->setAttrib("id", "altaSubdivisionesLibro");
		
		$subdivisionDAO = new Biblioteca_Data_DAO_Subdivision;
		$subdivisiones = $subdivisionDAO->getAllSubdivisiones();
		
		$eSubdivision = new Zend_Form_Element_Select("idsSubdivision");
		$eSubdivision->setLabel("Selecciona subdivision");
		$eSubdivision->setAttrib("class", "form-control");
		$eSubdivision->setRegisterInArrayValidator(FALSE);
		
		foreach ($subdivisiones as $subdivision) {
			$eSubdivision->addMultiOption($subdivision["idSubdivision"], $subdivision["subdivision"]);
		}
		
		$recursoDAO = new Biblioteca_DAO_Recurso;
		$recursos = $recursoDAO->getAllRecursos();
        
		$eRecurso = new Zend_Form_Element_Select("idRecurso");
		$eRecurso->setLabel("Selecciona un recurso");
		$eRecurso->setAttrib("class", "form-control");
		$eRecurso->setRegisterInArrayValidator(FALSE);
		
		foreach($recursos as $recurso){
			$eRecurso->addMultiOption($recurso->getIdRecurso(), $recurso->getTitulo());
		}
		
		$eSubmit =  new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn-btn-success");
		
		$this->addElement($eSubdivision);
		$this->addElement($eRecurso);
		$this->addElement($eSubmit);
    }

}


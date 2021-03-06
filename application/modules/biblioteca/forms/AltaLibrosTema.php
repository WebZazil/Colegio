<?php

class Biblioteca_Form_AltaLibrosTema extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $this->setAttrib("id", "altaLibrosTema");
        
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
		$recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
		$recursos = $recursoDAO->getAllTableRecursos();
		
		$eRecurso = new Zend_Form_Element_Select("idRecurso");
		$eRecurso->setLabel("Selecciona un recurso");
		$eRecurso->setAttrib("class", "form-control");
		$eRecurso->setRegisterInArrayValidator(FALSE);
		
		foreach($recursos as $recurso){
		    $eRecurso->addMultiOption($recurso['idRecurso'], $recurso['titulo']);
		}
        
		/*$eRecurso = new Zend_Form_Element_Select("idsRecurso");
		$eRecurso->setLabel("Selecciona un recurso");
		$eRecurso->setAttrib("class", "form-control");
		$eRecurso->setRegisterInArrayValidator(FALSE);
		
		foreach($recursos as $recurso){
			$eRecurso->addMultiOption($recurso->getIdRecurso(), $recurso->getTitulo());
		}*/
		
		
		$temaDAO = new Biblioteca_Data_DAO_Tema($identity['adapter']);
		$temas = $temaDAO->getAllTemas();
		
		$eTema = new Zend_Form_Element_Select("idTema");
		$eTema->setLabel("Selecciona un tema");
		$eTema->setAttrib("class", "form-control");
		$eTema->setRegisterInArrayValidator(FALSE);
		
		foreach ($temas as $tema) {
			$eTema->addMultiOption($tema["idTema"], $tema['tema']);
		}
		
		
		$eSubmit = new 	Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		$this->addElements(array($eRecurso,$eTema,$eSubmit));
		
    }


}


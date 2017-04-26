<?php

class Biblioteca_Form_AltaSubdivisionesLibro extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
               
        $this->setAttrib("id", "altaSubdivisionesLibro");
		
		$subdivisionDAO = new Biblioteca_DAO_Subdivision;
		$subdivisiones = $subdivisionDAO->getAllSubdivisiones();
		
		$eSubdivision = new Zend_Form_Element_Select("idsSubdivision");
		$eSubdivision->setLabel("Selecciona subdivision");
		$eSubdivision->setAttrib("class", "form-control");
		$eSubdivision->setRegisterInArrayValidator(FALSE);
		
		foreach ($subdivisiones as $subdivision) {
			$eSubdivision->addMultiOption($subdivision["idSubDivision"], $subdivision["subdivision"]);
		}
		
		$libroDAO = new Biblioteca_DAO_Libro;
		$libros = $libroDAO->getAllLibros();
        
		$eLibro = new Zend_Form_Element_Select("idLibro");
		$eLibro->setLabel("Selecciona un libro");
		$eLibro->setAttrib("class", "form-control");
		$eLibro->setRegisterInArrayValidator(FALSE);
		
		foreach($libros as $libro){
			$eLibro->addMultiOption($libro->getIdLibro(), $libro->getTitulo());
		}
		
		$eSubmit =  new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn-btn-success");
		
		$this->addElement($eSubdivision);
		$this->addElement($eLibro);
		$this->addElement($eSubmit);
    }

}


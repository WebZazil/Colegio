<?php

class Biblioteca_Form_AltaLibrosTema extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $this->setAttrib("id", "altaLibroTema");
		
		$libroDAO = new Biblioteca_DAO_Libro;
		$libros = $libroDAO->getAllLibros();
        
		$eLibro = new Zend_Form_Element_Select("idsLibro");
		$eLibro->setLabel("Selecciona un libro");
		$eLibro->setAttrib("class", "form-control");
		$eLibro->setRegisterInArrayValidator(FALSE);
		
		foreach($libros as $libro){
			$eLibro->addMultiOption($libro->getIdLibro(), $libro->getTitulo());
		}
		
		
		$temaDAO = new Biblioteca_DAO_Tema;
		$temas = $temaDAO->getAllTemas();
		
		$eTema = new Zend_Form_Element_Select("idTema");
		$eTema->setLabel("Selecciona un tema");
		$eTema->setAttrib("class", "form-control");
		$eTema->setRegisterInArrayValidator(FALSE);
		
		foreach ($temas as $tema) {
			$eTema->addMultiOption($tema["idTema"], $tema['Tema']);
		}
		
		
		$eSubmit = new 	Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		$this->addElement($eLibro);
		
		$this->addElement($eTema);
		
		$this->addElement($eSubmit);
    }


}


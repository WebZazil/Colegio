<?php

class Biblioteca_Form_ConsultaLibroPorMateria extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
                $materiaDAO = new Biblioteca_DAO_Materia;
		$materias = $materiaDAO->getAllMaterias();
		
        $eMateras = new Zend_Form_Element_Select("idMateria");
		$eMateras->setLabel("Materia: ");
		$eMateras->setAttrib("class", "form-control");
		
		foreach ($materias as $materia) {
			$eMateras->addMultiOption($materia["idMateria"], $materia["materia"]);
		}
		
        $eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Consultar");
		$eSubmit->setAttrib("class", "btn btn-info");
        
		$this->addElements(array($eMateras,$eSubmit));
    }


}


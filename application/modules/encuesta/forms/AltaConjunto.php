<?php

class Encuesta_Form_AltaConjunto extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $dbAdapter = Zend_Registry::get("dbmodquery");
		$grupoDAO = new Encuesta_DAO_Grupos($dbAdapter);
		$cicloDAO = new Encuesta_DAO_Ciclo($dbAdapter);
		
		$cicloActual = $cicloDAO->getCurrentCiclo();
		$gruposEscolares = $grupoDAO->getAllGruposByIdCicloEscolar($cicloActual->getIdCiclo());
        
        $eNombre = new Zend_Form_Element_Text("nombre");
		$eNombre->setLabel("Nombre del Conjunto");
		$eNombre->setAttrib("class", "form-control");
		$eNombre->setAttrib("placeholder", "Nombre Conjunto...");
		$eNombre->setAttrib("autofocus","");
		$eNombre->setRequired(true);
		
		$eGrupoEscolar = new Zend_Form_Element_Select("idGrupoEscolar");
		$eGrupoEscolar->setLabel("Grupo Escolar: ");
		$eGrupoEscolar->setAttrib("class", "form-control");
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setAttrib("class", "btn btn-success");
		$eSubmit->setLabel("Agregar Conjunto");
		
		foreach ($gruposEscolares as $grupoEscolar) {
			$eGrupoEscolar->addMultiOption($grupoEscolar["idGrupoEscolar"], $grupoEscolar["grupoEscolar"]);
		}
        
		$this->addElements(array($eNombre,$eGrupoEscolar,$eSubmit));
    }


}


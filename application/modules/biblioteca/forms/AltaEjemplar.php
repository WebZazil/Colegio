<?php

class Biblioteca_Form_AltaEjemplar extends Zend_Form
{
	
	private $util = null;
	
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $this->setAttrib("id","altaEjemplar");
		
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		/*============================================*/

		$recursoDAO = new Biblioteca_DAO_Recurso();
		$recursos = $recursoDAO->getAllRecursos();
		
		$eIdRecurso= new Zend_Form_Element_Select("idRecurso");
		$eIdRecurso->setLabel("Selecciona el título del recurso");
		$eIdRecurso->setAttrib("class", "form-control");
		$eIdRecurso->setRegisterInArrayValidator(FALSE);
		
		foreach ($recursos as $recurso) {
			$eIdRecurso->addMultiOption($recurso->getIdRecurso(), $recurso->getTitulo());
		}
		
		//===================================================
	    $editorialDAO = new Biblioteca_DAO_Editorial($identity["adapter"]);
	    $editoriales = $editorialDAO->getAllEditoriales();
		
		
		$eIdEditorial = new Zend_Form_Element_Select('idEditorial');
		$eIdEditorial->setLabel(' Selecciona Editorial: ');
		$eIdEditorial->setAttrib("class", "form-control");
		$eIdEditorial->setAttrib("required", "required");
		
		foreach ($editoriales as $editorial) {
			$eIdEditorial->addMultiOption($editorial['idEditorial'], $editorial["Editorial"]);
		}
	    
		//===================================================
		$ePublicado = new Zend_Form_Element_Text('publicado');
		//$ePublicado = new Zend_Form_Element_Xhtml('publicado');
		$ePublicado->setLabel('Año de publicación: ');
		$ePublicado->setAttrib("type", "number");
		$ePublicado->setAttrib("class", "form-control");
		$ePublicado->setAttrib("required", "required");
	    //=================================================
	    $idiomaDAO = new Biblioteca_DAO_Idioma($identity["adapter"]);
	    $idiomas = $idiomaDAO->getAllIdiomas();
		
		$eIdIdiomas = new Zend_Form_Element_Select("idIdioma");
		$eIdIdiomas->setLabel("Seleccione idioma");
		$eIdIdiomas->setAttrib("class", "form-control");
		$eIdIdiomas->setAttrib("required", "required");
		
		foreach ($idiomas as $idioma) {
			$eIdIdiomas->addMultiOption($idioma['idIdioma'], $idioma["idioma"]);
		}
		//====================================================
		
		$paisDAO = new Biblioteca_DAO_SubDivGeo($identity["adapter"]);
		$paises = $paisDAO->getAllPaises();
		
		$eIdPais = new Zend_Form_Element_Select('idPaisPub');
		$eIdPais->setLabel('Selecciona un país');
		$eIdPais->setAttrib("class", "form-control");
		$eIdPais->setAttrib("required", "required");
		
		foreach ($paises as $pais) {
			$eIdPais->addMultiOption($pais['idSubDivGeo'], $pais['pais']);
		}
	
		//===================================================
		
		$eNoClasif = new Zend_Form_Element_Text('noClasif');
		$eNoClasif->setLabel("Número de clasificación");
		$eNoClasif->setAttrib("class", "form-control");
		$eNoClasif->setAttrib("required", "required");
	    // ==================================================
	    $eNoItem = new Zend_Form_Element_Text('noItem');
		$eNoItem->setLabel("Número de ITEM");
		$eNoItem->setAttrib("class", "form-control");
		$eNoItem->setAttrib("required", "required");
	    //===================================================
	    $eNoEdicion = new Zend_Form_Element_Text('noEdicion');
		$eNoEdicion->setLabel('Número de edición');
		$eNoEdicion->setAttrib("class", "form-control");
		$eNoEdicion->setAttrib("required", "required");
	    //==================================================
	    $eIsbn = new Zend_Form_Element_Text("isbn");
		$eIsbn->setLabel("ISBN: ");
		$eIsbn->setAttrib("class", "form-control");
		$eIsbn->setAttrib("required", "required");
		//===================================================
		$eIssn = new Zend_Form_Element_Text('issn');
		$eIssn->setLabel("ISSN");
		$eIssn->setAttrib("class", "form-control");
		$eIssn->setAttrib("required", "required");
	    //===================================================

		$eVolumen = new Zend_Form_Element_Text('volumen');
		$eVolumen->setLabel("Volumen");
		$eVolumen->setAttrib("class", "form-control");
		$eVolumen->setAttrib("required", "required");
		
		//=================================================
		$eDimension = new Zend_Form_Element_Text('dimension');
		$eDimension->setLabel("Dimensiones");
		$eDimension->setAttrib("class","form-control");
		$eDimension->setAttrib("required", "required");
		//=================================================
	    $eSerie = new Zend_Form_Element_Text('serie');
		$eSerie->setLabel('Serie');
		$eSerie->setAttrib("class", "form-control");
		$eSerie->setAttrib("required", "required");
		//=================================================
		$ePaginas = new Zend_Form_Element_Text('paginas');
		$ePaginas->setLabel('Número de paginas: ');
		$ePaginas->setAttrib("class", "form-control");
		$ePaginas->setAttrib("required", "required");
		//=================================================

		$eAsientoPrin = new Zend_Form_Element_Text('asientoPrin');
		$eAsientoPrin->setLabel("Asiento Print");
		$eAsientoPrin->setAttrib("class", "form-control");
		$eAsientoPrin->setAttrib("required", "required");

		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		$this->addElement($eIdRecurso);
		$this->addElement($eIdEditorial);
		$this->addElement($ePublicado);
		$this->addElement($eIdIdiomas);
		$this->addElement($eNoClasif);
		$this->addElement($eNoItem);
		$this->addElement($eNoEdicion);
		$this->addElement($eIdPais);
		$this->addElement($eIsbn);
		$this->addElement($eIssn);
		$this->addElement($eVolumen);
		$this->addElement($eDimension);
		$this->addElement($eSerie);
		$this->addElement($ePaginas);
		$this->addElement($eAsientoPrin);
		$this->addElement($eSubmit);
    }


}


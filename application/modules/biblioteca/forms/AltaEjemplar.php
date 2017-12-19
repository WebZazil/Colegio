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
	    //$editorialDAO = new Biblioteca_DAO_Editorial($identity["adapter"]);
	    $editorialDAO = new Biblioteca_DAO_Editorial();
	    $editoriales = $editorialDAO->getAllEditoriales();
		
		
		$eIdEditorial = new Zend_Form_Element_Select('idEditorial');
		$eIdEditorial->setLabel(' Selecciona Editorial: ');
		$eIdEditorial->setAttrib("class", "form-control");
		
		foreach ($editoriales as $editorial) {
			$eIdEditorial->addMultiOption($editorial['idEditorial'], $editorial["editorial"]);
		}
	    
		//===================================================
		$ePublicado = new Zend_Form_Element_Text('publicado');
		//$ePublicado = new Zend_Form_Element_Xhtml('publicado');
		$ePublicado->setLabel('Año de publicación: ');
		$ePublicado->setAttrib("type", "number");
		$ePublicado->setAttrib("class", "form-control");
		$ePublicado->setAttrib("required", "required");
	    //=================================================
	    //$idiomaDAO = new Biblioteca_DAO_Idioma($identity["adapter"]);
	    $idiomaDAO = new Biblioteca_Data_DAO_Idioma();
	    $idiomas = $idiomaDAO->getAllIdiomas();
		
		$eIdIdiomas = new Zend_Form_Element_Select("idIdioma");
		$eIdIdiomas->setLabel("Seleccione idioma");
		$eIdIdiomas->setAttrib("class", "form-control");
		$eIdIdiomas->setAttrib("required", "required");
		
		foreach ($idiomas as $idioma) {
			$eIdIdiomas->addMultiOption($idioma['idIdioma'], $idioma["idioma"]);
		}
		//====================================================
		
		//$paisDAO = new Biblioteca_DAO_SubDivGeo($identity["adapter"]);
		$paisDAO = new Biblioteca_DAO_SubDivGeo();
		$paises = $paisDAO->getAllPaises();
		
		$eIdPais = new Zend_Form_Element_Select('idSubDivGeo');
		$eIdPais->setLabel('Selecciona un país');
		$eIdPais->setAttrib("class", "form-control");
		
		foreach ($paises as $pais) {
			$eIdPais->addMultiOption($pais['idSubDivGeo'], $pais['pais']);
		}
	
		//===================================================
		
		$eNoClasif = new Zend_Form_Element_Text('noClasif');
		$eNoClasif->setLabel("Número de clasificación");
		$eNoClasif->setAttrib("class", "form-control");
		
	    // ==================================================
	    $eNoItem = new Zend_Form_Element_Text('noItem');
		$eNoItem->setLabel("Número de ITEM");
		$eNoItem->setAttrib("class", "form-control");
	
	    //===================================================
	    $eNoEdicion = new Zend_Form_Element_Text('noEdicion');
		$eNoEdicion->setLabel('Número de edición');
		$eNoEdicion->setAttrib("class", "form-control");
		
	    //==================================================
	    $eIsbn = new Zend_Form_Element_Text("isbn");
		$eIsbn->setLabel("ISBN: ");
		$eIsbn->setAttrib("class", "form-control");
	
		//===================================================
		$eIssn = new Zend_Form_Element_Text('issn');
		$eIssn->setLabel("ISSN");
		$eIssn->setAttrib("class", "form-control");
	
	    //===================================================

		$eVolumen = new Zend_Form_Element_Text('volumen');
		$eVolumen->setLabel("Volumen");
		$eVolumen->setAttrib("class", "form-control");
		
		
		//=================================================
		$eDimension = new Zend_Form_Element_Text('dimension');
		$eDimension->setLabel("Dimensiones");
		$eDimension->setAttrib("class","form-control");
		$eDimension->setAttrib("required", "required");
		//=================================================
	    $eSerie = new Zend_Form_Element_Text('serie');
		$eSerie->setLabel('Serie');
		$eSerie->setAttrib("class", "form-control");

		//=================================================
		$ePaginas = new Zend_Form_Element_Text('paginas');
		$ePaginas->setLabel('Número de paginas: ');
		$ePaginas->setAttrib("class", "form-control");
		$ePaginas->setAttrib("required", "required");
		//=================================================
		

		$eAsientoPrin = new Zend_Form_Element_Text('asientoPrin');
		$eAsientoPrin->setLabel("Asiento Print");
		$eAsientoPrin->setAttrib("class", "form-control");
		//=================================================
		$eNota = new Zend_Form_Element_Text('nota');
		$eNota->setLabel("Nota(s)");
		$eNota->setAttrib("class","form-control");
		//=================================================

		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		$this->addElements(array($eIdRecurso,$eIdEditorial,$ePublicado,$eIdIdiomas,$eNoClasif,$eNoItem,$eNoEdicion,
		$eIdPais,$eIsbn,$eIssn,$eVolumen,$eDimension,$eSerie,$ePaginas,$eAsientoPrin,$eNota,$eSubmit));
	
    }


}


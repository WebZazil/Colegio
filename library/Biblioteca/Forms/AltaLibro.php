<?php
/**
 * 
 */
class Biblioteca_Forms_AltaLibro extends Zend_Form {
	
	private $util = null;
	
	public function init() {
		$this->util = new Biblioteca_Forms_FormSteps;
		
		$subUno = new Zend_Form_SubForm('basico');
		$subUno->setLegend('Información Básica');
		
		// ==================================================
		// agregando elementos a primer subforma
		$eTitulo = new Zend_Form_Element_Text('titulo');
		$eTitulo->setLabel("Titulo: ");
		$eTitulo->setAttrib("autofocus", "");
		$eTitulo->setAttrib("class", "form-control");
		$eTitulo->setAttrib("required", "required");
		//===================================================
		$eAutor = new Zend_Form_Element_Text('autor');
		$eAutor->setLabel("Autor: ");
		$eAutor->setAttrib("class", "form-control");
		$eAutor->setAttrib("required", "required");
		//===================================================
		$eEditorial = new Zend_Form_Element_Text('editorial');
		$eEditorial->setLabel('Editorial: ');
		$eEditorial->setAttrib("class", "form-control");
		$eEditorial->setAttrib("required", "required");
		//===================================================
		$ePublicado = new Zend_Form_Element_Text('publicado');
		//$ePublicado = new Zend_Form_Element_Xhtml('publicado');
		$ePublicado->setLabel('Año de publicación: ');
		$ePublicado->setAttrib("type", "number");
		$ePublicado->setAttrib("class", "form-control");
		$ePublicado->setAttrib("required", "required");
		$ePublicado->setAttrib("min", "1900");
		$ePublicado->setAttrib("max", "2020"); //Advertencia!! error si se rebasa este valor con la fecha actual
		$ePublicado->setAttrib("step", "1");
		$ePublicado->setAttrib("value", "2017");
		
		/*$view = new Zend_View();
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$ePublicado = new ZendX_Jquery_Form_Element_Spinner("spinner1",
		array('label'=>'Spinner:','attribs'=>array('class'=>'flora')));
		$ePublicado->setJQueryParams(array('min'=>0,'max'=>1000,'start'=>100));*/
		
		//=================================================
		$eIdPais = new Zend_Form_Element_Text('idPaisPub');
		$eIdPais->setLabel('Pais de publicación');
		$eIdPais->setAttrib("class", "form-control");
		$eIdPais->setAttrib("required", "required");
	
		//===================================================
		
		// agregando elementos a subforma uno
		$subUno->addElements(array($eTitulo,$eAutor,$eEditorial,$ePublicado,$eIdPais));
		$subUno->setElementDecorators($this->util->getMTextElementDecorators());
		$subUno->setDecorators($this->util->getMSubFormDecorators());
		
		//Creando segunda subforma
		$subDos = new Zend_Form_SubForm('clasf1');
		$subDos->setLegend("Atributos");
		
		$eIdClasificacion = new Zend_Form_Element_Text('idClasificacion');
		$eIdClasificacion->setLabel('Clasificación: ');
		$eIdClasificacion->setAttrib("class", "form-control");
		$eIdClasificacion->setAttrib("required", "required");
	    //===================================================
		$eNoClasif = new Zend_Form_Element_Text('noClasif');
		$eNoClasif->setLabel("Clasificación");
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
	    
	    // agregando elementos a subforma dos
		$subDos->addElements(array($eIdClasificacion,$eNoClasif,$eNoItem,$eNoEdicion,$eIsbn,$eIssn));
		$subDos->setElementDecorators($this->util->getMTextElementDecorators());
		$subDos->setDecorators($this->util->getMSubFormDecorators());
	    
		//Creando tercer subforma
		$subTres = new Zend_Form_SubForm('clasf2');
		$subTres->setLegend("Caracteristicas");
		
		// ==================================================
		// agregando elementos a la tercera subforma
		
		$eIdColeccion = new Zend_Form_Element_Text("idColecion");
		$eIdColeccion->setLabel("Colección");
		$eIdColeccion->setAttrib("class", "form-control");
		$eIdColeccion->setAttrib("required", "required");
		//=================================================
		$eVolumen = new Zend_Form_Element_Text('volumen');
		$eVolumen->setLabel("Volumen");
		$eVolumen->setAttrib("class", "form-control");
		$eVolumen->setAttrib("required", "required");
		//===================================================
		$eIdMaterial = new Zend_Form_Element_Text('idMaterial');
		$eIdMaterial->setLabel("Material");
		$eIdMaterial->setAttrib("class", "form-control");
		$eIdMaterial->setAttrib("required", "required");
	
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
		
		// agregando elementos a subforma tres
		
		$subTres->addElements(array($eIdColeccion,$eVolumen,$eIdMaterial,$eDimension,$eSerie,$ePaginas));
		$subTres->setElementDecorators($this->util->getMTextElementDecorators());
		$subTres->setDecorators($this->util->getMSubFormDecorators());
		
		//Creando cuarta subforma
		$subCuatro= new Zend_Form_SubForm('clasf3');
		$subCuatro->setLegend("Etiquetas");
	    
		//=================================================
		$eAsientoPrin = new Zend_Form_Element_Text('asientoPrin');
		$eAsientoPrin->setLabel("Asiento Print");
		$eAsientoPrin->setAttrib("class", "form-control");
		$eAsientoPrin->setAttrib("required", "required");
		
		//=================================================
		$eEti008 = new Zend_Form_Element_Text('eti008');
		$eEti008->setLabel("Etiqueta 008");
		$eEti008->setAttrib("class", "form-control");
		$eEti008->setAttrib("required", "required");
		//=================================================
		$eEtiLDR = new Zend_Form_Element_Text('etiLDR');
		$eEtiLDR->setLabel("Etiqueta LDR: ");
		$eEtiLDR->setAttrib("class", "form-control");
		$eEtiLDR->setAttrib("required", "required");
		//=================================================
		$eEtiqueta = new Zend_Form_Element_Text('etiqueta');
		$eEtiqueta->setLabel("Etiqueta :");
		$eEtiqueta->setAttrib("class", "form-control");
		$eEtiqueta->setAttrib("required", "required");
		
		//================================================
		
		// agregando elementos a subforma cuatro
		
		$subCuatro->addElements(array($eAsientoPrin,$eEti008,$eEtiLDR,$eEtiqueta));
		$subCuatro->setElementDecorators($this->util->getMTextElementDecorators());
		$subCuatro->setDecorators($this->util->getMSubFormDecorators());
		
		
		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		// agregar subformas a formulario
		//$formSteps->addSubForm($subUno);
		$this->addSubForms(array($subUno,$subDos,$subTres,$subCuatro));
		$this->setDecorators($this->util->getMFormDecorators());
		
		$this->addElement($eSubmit);
	}
}

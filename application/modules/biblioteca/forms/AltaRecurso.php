<?php

class Biblioteca_Form_AltaRecurso extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $this->setAttrib("id","altaRecurso");
		
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		
	
		/*============================================*/
		$eTitulo = new Zend_Form_Element_Text('titulo');
		$eTitulo->setLabel("Título: ");
		$eTitulo->setAttrib("autofocus", "");
		$eTitulo->setAttrib("class", "form-control");
		$eTitulo->setAttrib("required", "required");
		//===================================================
		$eSubtitulo = new Zend_Form_Element_Text('subtitulo');
		$eSubtitulo->setLabel("Subtítulo: ");
		$eSubtitulo->setAttrib("class", "form-control");
	
		//===================================================
		$opts = array('PEN' => 'Pendiente', 'IND' => 'Individual', 'VAR' => 'Varios');
		
		$eTipoAutor = new Zend_Form_Element_Select('tipoAutor');
		$eTipoAutor->setLabel('Tipo de Autor:');
		$eTipoAutor->setAttrib('class', 'form-control');
		$eTipoAutor->setMultiOptions($opts);
		
		$autorDAO = new Biblioteca_Data_DAO_Autor();
		$autores = $autorDAO->getAllAutores();
		
		 
		
		$eIdAutor = new Zend_Form_Element_Select('idsAutores');
		$eIdAutor->setLabel("Seleccione autor ");
		$eIdAutor->setAttrib("class", "form-control");
		$eIdAutor->setAttrib("required", "required");
		/*
		foreach ($autores as $autor) {
			$eIdAutor->addMultiOption($autor['idAutor'],$autor['nombres']);
		}*/
		
	  /*  foreach ($autores as $autor) {
			$eIdAutor->addMultiOption($autor['idAutor'],$autor['autores']);
	    }*/
		
		//===================================================
		$materialDAO = new Biblioteca_Data_DAO_Material($identity["adapter"]);
		$materiales = $materialDAO->getAllMateriales();
		
		$eIdMaterial = new Zend_Form_Element_Select('idMaterial');
		$eIdMaterial->setLabel("Selecciona un material");
		$eIdMaterial->setAttrib("class", "form-control");
		$eIdMaterial->setAttrib("required", "required");
		
		foreach ($materiales as $material) {
			$eIdMaterial->addMultiOption($material['idMaterial'], $material['descripcion']);
		}
		//=================================================
		//$clasificacionDAO = new Biblioteca_DAO_Clasificacion($identity["adapter"]);
		$clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion();
		$clasificaciones = $clasificacionDAO->getAllClasificaciones();
	
		$eIdClasificacion = new Zend_Form_Element_Select('idClasificacion');
		$eIdClasificacion->setLabel('Selecciona una clasificación ');
		$eIdClasificacion->setAttrib("class", "form-control");
		$eIdClasificacion->setAttrib("required", "required");
		
		foreach ($clasificaciones as $clasificacion) {
	    	$eIdClasificacion->addMultiOption($clasificacion['idClasificacion'], $clasificacion['clasificacion']);
		}
		// ==================================================
		//$coleccionDAO = new Biblioteca_DAO_Coleccion($identity["adapter"]);
		$coleccionDAO = new Biblioteca_Data_DAO_Coleccion();
		$colecciones = $coleccionDAO->getAllColecciones();
		
		$eIdColeccion = new Zend_Form_Element_Select("idColeccion");
		$eIdColeccion->setLabel("Selecciona una colección");
		$eIdColeccion->setAttrib("class", "form-control");
		$eIdColeccion->setAttrib("required", "required");
		
		foreach ($colecciones as $coleccion) {
	    	$eIdColeccion->addMultiOption($coleccion['idColeccion'], $coleccion['coleccion']);
		}
		//=================================================
		$tipoExtras = array("ninguno"=>"Ninguno","il"=>"Ilustraciones","maps"=>"Mapas","retrs"=>"Retratos","tbs"=>"Tablas",);
		
		$eTipoExtra = new Zend_Form_Element_Select("extra");
        $eTipoExtra->setLabel("Material ilustrativo: ");
        $eTipoExtra->setAttrib("class", "form-control");
        $eTipoExtra->setMultiOptions($tipoExtras);
		//=================================================
		$eNota = new Zend_Form_Element_Text('nota');
		$eNota->setLabel("Nota(s)");
		$eNota->setAttrib("class","form-control");
		
		//=================================================
		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		
		$this->addElements(array($eTitulo, $eSubtitulo, $eTipoAutor, $eIdAutor, $eIdMaterial, $eIdClasificacion,$eIdColeccion, $eTipoExtra,$eNota,$eSubmit));
	

	}
        
}


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
		$eSubtitulo->setAttrib("required", "required");
		//===================================================
		$autorDAO = new Biblioteca_DAO_Autor();
		$autores = $autorDAO->getAllAutores();
		
		$eIdAutor = new Zend_Form_Element_Select('idAutor');
		$eIdAutor->setLabel("Seleccione autor ");
		$eIdAutor->setAttrib("class", "form-control");
		$eIdAutor->setAttrib("required", "required");
		
		foreach ($autores as $autor) {
			$eIdAutor->addMultiOption($autor['idAutor'],$autor["nombre"]);
		}
		//===================================================
		$materialDAO = new Biblioteca_DAO_Material($identity["adapter"]);
		$materiales = $materialDAO->getAllMateriales();
		
		$eIdMaterial = new Zend_Form_Element_Select('idMaterial');
		$eIdMaterial->setLabel("Selecciona un material");
		$eIdMaterial->setAttrib("class", "form-control");
		$eIdMaterial->setAttrib("required", "required");
		
		foreach ($materiales as $material) {
			$eIdMaterial->addMultiOption($material->getIdMaterial(), $material->getMaterial());
		}
		//=================================================
		$clasificacionDAO = new Biblioteca_DAO_Clasificacion($identity["adapter"]);
		$clasificaciones = $clasificacionDAO->getClasificaciones();
	
		$eIdClasificacion = new Zend_Form_Element_Select('idClasificacion');
		$eIdClasificacion->setLabel('Selecciona una clasificación ');
		$eIdClasificacion->setAttrib("class", "form-control");
		$eIdClasificacion->setAttrib("required", "required");
		
		foreach ($clasificaciones as $clasificacion) {
	    	$eIdClasificacion->addMultiOption($clasificacion['idClasificacion'], $clasificacion['clasificacion']);
		}
		// ==================================================
		$coleccionDAO = new Biblioteca_DAO_Coleccion($identity["adapter"]);
		$colecciones = $coleccionDAO->getColecciones();
		
		$eIdColeccion = new Zend_Form_Element_Select("idColeccion");
		$eIdColeccion->setLabel("Selecciona una colección");
		$eIdColeccion->setAttrib("class", "form-control");
		$eIdColeccion->setAttrib("required", "required");
		
		foreach ($colecciones as $coleccion) {
	    	$eIdColeccion->addMultiOption($coleccion->getIdColeccion(), $coleccion->getDescripcion());
		}
		//=================================================
		$eNota = new Zend_Form_Element_Text('nota');
		$eNota->setLabel("Nota(s)");
		$eNota->setAttrib("class","form-control");
		$eNota->setAttrib("required", "required");
		//=================================================
		$eSubmit = new Zend_Form_Element_Submit('submit');
		$eSubmit->setLabel("Guardar");
		$eSubmit->setAttrib("class", "btn btn-success");
		
		
		$this->addElement($eTitulo);
		$this->addElement($eSubtitulo);
		$this->addElement($eIdAutor);
		$this->addElement($eIdMaterial);
		$this->addElement($eIdClasificacion);
		$this->addElement($eIdColeccion);
		$this->addElement($eNota);
		
		$this->addElement($eSubmit);
	}
        
}


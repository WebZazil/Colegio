<?php

class Biblioteca_JsonController extends Zend_Controller_Action
{

    private $autorDAO = null;
    private $recursoDAO = null;
    private $materialDAO = null;
    private $coleccionDAO = null;
    private $clasificacionDAO = null;
    private $ejemplarDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->autorDAO = new Biblioteca_Data_DAO_Autor;
        
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
        $this->materialDAO = new Biblioteca_Data_DAO_Material($identity['adapter']);
        $this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($identity['adapter']);
        $this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($identity['adapter']);
        $this->ejemplarDAO = new Biblioteca_Data_DAO_Ejemplar($identity['adapter']);
		
    }

    public function indexAction()
    {
        // action body
    }

    public function autoresiAction()
    {
        // action body
        $autores = $this->autorDAO->getAutoresIndividuales();
		
		echo Zend_Json::encode($autores);
    }

    public function autoresvAction()
    {
        // action body
        $autores = $this->autorDAO->getAutoresVarios();
		
		echo Zend_Json::encode($autores);
    }

    public function brecursosAction()
    {
        // action body
        $claveRecurso = $this->getParam('cr');
        //$recursos = $this->recursoDAO->getRecursoByParams(array('codBarrOrigen'=>$claveRecurso));
        $copia = $this->ejemplarDAO->getCopiaEjemplarByBarcode($claveRecurso);
        $ejemplar = $this->ejemplarDAO->getObjectEjemplar($copia['idEjemplar']);
        $recurso = $this->recursoDAO->getObjectRecurso($ejemplar['ejemplar']['idRecurso']);
        
        $container = array();
        $container['copia'] = $copia;
        $container['ejemplar'] = $ejemplar;
        $container['recurso'] = $recurso;
        
        echo Zend_Json::encode($container);
    }

    public function loginuAction()
    {
        // action body
    }


}










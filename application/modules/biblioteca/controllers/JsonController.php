<?php

class Biblioteca_JsonController extends Zend_Controller_Action
{

    private $autorDAO = null;
    private $recursoDAO = null;
    private $materialDAO = null;
    private $coleccionDAO = null;
    private $clasificacionDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->autorDAO = new Biblioteca_Data_DAO_Autor;
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
        $this->materialDAO = new Biblioteca_Data_DAO_Material($identity['adapter']);
        $this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($identity['adapter']);
        $this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($identity['adapter']);
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
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
        $recursos = $this->recursoDAO->getRecursoByParams(array('codBarrOrigen'=>$claveRecurso));
        $estatusRecurso = $this->recursoDAO->getEstatusRecurso();
        $materiales = $this->materialDAO->getAllMateriales();
        $colecciones = $this->coleccionDAO->getAllColecciones();
        $clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        //print_r($recursos);
        //print_r($estatusRecurso);
        
        $container = array();
        
        foreach ($recursos as $recurso) {
            $obj = array();
            $obj['recurso'] = $recurso;
            # Buscando en estatus
            foreach ($estatusRecurso as $eRecurso){
                if ($eRecurso['idEstatusRecurso'] == $recurso['idEstatusRecurso']) {
                    $obj['estatus'] = $eRecurso;
                }
            }
            # Buscando en material
            foreach ($materiales as $material){
                if ($material['idMaterial'] == $recurso['idMaterial']) {
                    $obj['material'] = $material;
                }
            }
            # Buscando en coleccion
            foreach ($colecciones as $coleccion){
                if ($coleccion['idColeccion'] == $recurso['idColeccion']) {
                    $obj['coleccion'] = $coleccion;
                }
            }
            # Buscando en clasificacion
            foreach ($clasificaciones as $clasificacion){
                if ($clasificacion['idClasificacion'] == $recurso['idClasificacion']) {
                    $obj['clasificacion'] = $clasificacion;
                }
            }
            
            $container[] = $obj;
        }
        /*
        foreach ($container as $obj){
            //print_r($obj); print_r('<br /><br />');
        }
        */
        echo Zend_Json::encode($container);
    }


}








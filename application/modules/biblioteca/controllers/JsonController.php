<?php

class Biblioteca_JsonController extends Zend_Controller_Action
{

    private $autorDAO = null;
    private $recursoDAO = null;
    private $materialDAO = null;
    private $coleccionDAO = null;
    private $clasificacionDAO = null;
    private $ejemplarDAO = null;
    
    private $testConnector = null;
    private $serviceLogin = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        $this->serviceLogin = new Biblioteca_Service_Login();
        
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $this->testConnector = $this->serviceLogin->getTestConnection($testData, 'colsagcor16', 'MOD_BIBLIOTECA');
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->autorDAO = new Biblioteca_Data_DAO_Autor($this->testConnector);
        
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($this->testConnector);
        $this->materialDAO = new Biblioteca_Data_DAO_Material($this->testConnector);
        $this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($this->testConnector);
        $this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($this->testConnector);
        $this->ejemplarDAO = new Biblioteca_Data_DAO_Ejemplar($this->testConnector);
		
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

    public function brecindexAction()
    {
        // action body
        $params = $this->getAllParams();
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        
        $parametros = array();
        
        if(array_key_exists('to', $params)){
            $parametros['titulo'] = $params['to'];
        }
        if(array_key_exists('sb', $params)){
            $parametros['subtitulo'] = $params['sb'];
        }
        if(array_key_exists('mt', $params)){
            $parametros['idMaterial'] = $params['mt'];
        }
        if(array_key_exists('co', $params)){
            $parametros['idColeccion'] = $params['co'];
        }
        if(array_key_exists('cl', $params)){
            $parametros['idClasificacion'] = $params['cl'];
        }
        
        $recursos = $this->recursoDAO->getRecursoByParams($parametros);
        $container = array();
        foreach ($recursos as $recurso){
            $obj = array();
            // Obtenemos el recurso
            $obj['recurso'] = $this->recursoDAO->getObjectRecurso($recurso['idRecurso']);
            // Obtenemos todos los ejemplares de recurso
            $ejemplares = $this->ejemplarDAO->getObjectEjemplaresRecurso($recurso['idRecurso']);
            $obj['ejemplares'] = $ejemplares;
            // Obtenemos todas las copias de todos los ejemplares
            $inventario = array();
            foreach ($ejemplares as $ejemplar){
                $e = $ejemplar['ejemplar'];
                $copias = $this->ejemplarDAO->getCopiasEjemplar($e['idEjemplar']);
                
                foreach ($copias as $copia){
                    $inventario[] = $copia;
                }
            }
            
            $obj['inventario'] = $inventario;
            
            $container[] = $obj;
        }
        
        //print_r($container); print_r('<br /><br />');
        
        
        //$container = array();
        echo Zend_Json::encode($container);
    }

    public function brecbybcAction()
    {
        // action body
        //print_r('Hola');
        $tipoBarcode = $this->getParam('tb');
        $barcode = $this->getParam('bc');
        if ($tipoBarcode == 'CP') {
            $copia = $this->ejemplarDAO->getCopiaEjemplarByBarcode($barcode);
            //print_r($copia);
            $ejemplar = $this->ejemplarDAO->getObjectEjemplar($copia['idEjemplar']);
            //print_r($ejemplar);
            $recurso = $this->recursoDAO->getObjectRecurso($ejemplar['ejemplar']['idRecurso']);
            //print_r($recurso);
            
            $contenedor = array();
            $contenedor['copia'] = $copia;
            $contenedor['ejemplar'] = $ejemplar;
            $contenedor['recurso'] = $recurso;
            
            echo Zend_Json::encode($contenedor);
        }
    }
}














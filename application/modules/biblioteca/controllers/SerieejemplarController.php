<?php

class Biblioteca_SerieejemplarController extends Zend_Controller_Action
{

    private $serieEjemplarDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if(! $auth->hasIdentity()){
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        
        $identity = $auth->getIdentity();
        
        $dbAdapter = $identity['adapter'];
        $this->serieEjemplarDAO = new Biblioteca_Data_DAO_SerieEjemplar($dbAdapter);
        
    }

    public function indexAction()
    {
        // action body
        
        $seriesDAO = $this->serieEjemplarDAO;
        
        $series = $this->serieEjemplarDAO->getAllSeries();
        
        $this->view->series = $series;
        $request = $this->getRequest();
        
        if($request->isPost()){
            $series = $request->getPost();
            print_r($series); print_r("<br />");
            
            
            foreach ($series as $key => $value){
                if ($value == "0") {
                    unset($series[$key]);
                }
            }
            
            
            $resources = $this->serieEjemplarDAO->getSeriesByParamas($series);
            if (!empty($resources)){
                
                $container = array();
                
                $container = $resources;
                $this->view->resources = $container;
            }else{
                $this->view->resources = $array;
            }
            
            
        }else{
            $this->view->resources = array();
        }
        
        
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        
        if ($request->isPost()){
            
            $datos = $request->getPost();
            $datos["creacion"] = date("Y-m-d h:i:s", time());
            
            try {
                $this->serieEjemplarDAO->altaEjemplar($datos);
                $this->view->messageSuccess = "Serie Ejemplar dada de alta exitosamente.";
            }catch (Exception $ex){
                $this->view->messageFail = "Error en altqa de Serie Ejemplar <strong>".$ex->getMessage()."</strong>";
                
            }
        }
        
        
    }

    public function adminAction()
    {
        // action body
        $request = $this->getRequest();
        $idSeriesEjemplar = $this->getParam('sre');
        
        print_r($idSeriesEjemplar);
        
        $serieejemplar = $this->serieEjemplarDAO->getSeriesEjemplarById($idSeriesEjemplar);
        
        
        $this->view->serieejemplar = $serieejemplar;
        
        
        
        if($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                
                $idSeriesEjemplar = $this->serieEjemplarDAO->editarSerieEjemplar($idSeriesEjemplar, $datos);
                $this->view->messageSuccess ="Serie Ejemplar: <strong>".$datos['nombreSerie']."</strong> ha sido modificada";
                
            }catch (Exception $ex){
                $this->view->messageFail = "La clasificación: <strong>".$datos['nombreSerie']."</strong> no ha sido modificada. Error: <strong>".$ex->getMessage()."<strong>";
            }
            
        }
    }
    
    
}









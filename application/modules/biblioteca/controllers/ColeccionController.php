<?php

class Biblioteca_ColeccionController extends Zend_Controller_Action
{
	
    private $coleccionDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if(! $auth->hasIdentity()){
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        
        $identity = $auth->getIdentity();
        
        //$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        //$colecciones = $this->coleccionDAO->getAllColecciones();
		//$this->view->colecciones = $colecciones;
		
        $colecciones = $this->coleccionDAO->getAllColecciones();
        
        $this->view->colecciones = $colecciones;
        $request = $this->getRequest();
        
        if($request->isPost()){
            $colecciones =$request->getPost();
            print_r($colecciones); print_r("<br />");
            
            
            foreach ($colecciones as $key => $value){
                if($value == "0"){
                    unset($coleccion[$key]);
                }
            }
            
            $resources = $this->coleccionDAO->getEditorialByParamas($colecciones);
            if(!empty($resources)){
                
                $container = array();
                
                $container = $resources;
                $this->view->resources = $container;
                
            }else{
                $this->view->resources = $array;
            }
            
            
            
        }else{
            $this->view->resources = array();
        }
        
        $coleccionDAO   = $this->coleccionDAO;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaColeccion;
        $this->view->formulario = $formulario;
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    $this->coleccionDAO->addColeccion($data);
                    $this->view->messageSuccess = "Colección dada de de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de colección <strong>".$ex->getMessage()."</strong>";
                }
            }
        }
    }

    public function editaAction()
    {
        // action body
    }

    public function adminAction()
    {
        // action body
        
        
        $request = $this->getRequest();
        $idColeccion = $this->getParam('cln');
        
        //print_r($idClasificacion);
        
        $colecciones = $this->coleccionDAO->getColeccionById($idColeccion);
        
        
        $this->view->colecciones = $colecciones;
        
        if($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                
                $idColeccion = $this->coleccionDAO->editarColeccion($idColeccion, $datos);
                $this->view->messageSuccess ="La coleccion: <strong>".$datos['coleccion']."</strong> ha sido modificado";
                
            }catch (Exception $ex){
                $this->view->messageFail = "La coleccion: <strong>".$datos['coleccion']."</strong> no ha sido modificada. Error: <strong>".$ex->getMessage()."<strong>";
            }
    }
    }

}








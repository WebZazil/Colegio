<?php

class Biblioteca_TemaController extends Zend_Controller_Action
{
	
	private $temaDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        $identity = $auth->getIdentity();
        
        //$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->temaDAO = new Biblioteca_Data_DAO_Tema($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        
       // $temas = $this->temaDAO->getAllTemas();
		//$this->view->temas = $temas;
        $temas = $this->temaDAO->getAllTemas();
        
        $this->view->temas = $temas;
        $request = $this->getRequest();
        
        if($request->isPost()){
            $temas =$request->getPost();
            print_r($temas); print_r("<br />");
            
            
            foreach ($temas as $key => $value){
                if($value == "0"){
                    unset($tema[$key]);
                }
            }
            
            $resources = $this->temaDAO->getEditorialByParamas($temas);
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
        
        $temaDAO   = $this->temaDAO;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaTema;
        $this->view->formulario = $formulario;
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    $this->temaDAO->addTema($datos);
                    $this->view->messageSuccess = "Tema dado de de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de tema <strong>".$ex->getMessage()."</strong>";
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
        $idTema = $this->getParam("tm");
        
        //print_r($idMaterial);
        
        $temas = $this->temaDAO->getTemaById($idTema);
        
        $this->view->temas = $temas;
        
        
        if($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                
                $idTema = $this->temaDAO->editarTema($idTema, $datos);
                $this->view->messageSuccess ="Tema: <strong>".$datos['tema']."</strong> ha sido modificado";
                
            }catch (Exception $ex){
                $this->view->messageFail = "El tema: <strong>".$datos['tema']."</strong> no ha sido modificada. Error: <strong>".$ex->getMessage()."<strong>";
            }
            
        }
    
    }
 


}








<?php

class Biblioteca_PaisController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        $identity = $auth->getIdentity();
        
        //$dbAdapter = Zend_Registry::get("dbmodqueryb");
        
        $this->paisDAO = new Biblioteca_Data_DAO_Pais($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        
        
        $paises = $this->paisDAO->getAllPaises();
        
        $this->view->paises = $paises;
        $request = $this->getRequest();
        
        if($request->isPost()){
            $paises =$request->getPost();
            print_r($paises); print_r("<br />");
            
            
            foreach ($paises as $key => $value){
                if($value == "0"){
                    unset($pais[$key]);
                }
            }
            
            $resources = $this->paisDAO->getPaisById($idPais);
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
        
        $paisDAO = $this->paisDAO;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaPais();
        $this->view->formulario = $formulario;     
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    $this->paisDAO->addPais($data);
                    $this->view->messageSuccess = "Pais dado de de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de pais <strong>".$ex->getMessage()."</strong>";
                }
            }
        }
    
        
    }

    public function adminAction()
    {
        // action body
        
        $request = $this->getRequest();
        $idPais = $this->getParam("ps");
        
        print_r($idPais);
        
        $paises = $this->paisDAO->getPaisById($idPais);
        
        $this->view->paises = $paises;
        
        
        if($request->isPost()){
            $datos = $request->getPost();
            
            try{
                
                $idPais = $this->paisDAO->editarPais($idPais, $datos);
                $this->view->messageSuccess ="Pais: <strong>".$datos['nombre']."</strong> ha sido modificado";
                
            }catch (Exception $ex){
                $this->view->messageFail ="El pais: <strong>".$datos['nombre']."</strong> no ha sido modificado. Error: <strong>".$ex->getMessage()."</strong>";
            }
          
        }
        
        
        
    
    }


}






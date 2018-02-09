<?php

class Biblioteca_AutorController extends Zend_Controller_Action
{
    private $autorDAO;
    

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if(! $auth->hasIdentity()){
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        
        $identity = $auth->getIdentity();
        //$dbAdapter = Zend_Registry::get("dbmodqueryb");
                
        $this->autorDAO = new Biblioteca_Data_DAO_Autor($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
       // $autores = $this->autorDAO->getAllAutores();
        //$this->view->autores = $autores;
        
        $autores = $this->autorDAO->getAllAutores();
        
        $this->view->autores = $autores;
        $request = $this->getRequest();
        
        if($request->isPost()){
            $autores = $request->getPost();
            print_r($autores); print_r("<br />");
            
            foreach ($autores as $key => $value){
                if($value == "0"){
                    unset($editorial[$key]);
                }
            }
            
            $resources = $this->autorDAO->getEditorialByParamas($autores);
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
        
        $autorDAO   = $this->autorDAO;
        
        
    }

    public function altaAction() {
        // action body
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaAutor;
        $this->view->formulario = $formulario;
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    switch ($datos["tipo"]) {
                        case 'UN':
                            $datos["autores"] = "";
                            break;
                        case 'VR':
                        case 'IN':
                            $datos["nombres"] = "";
                            $datos["apellidos"] = "";
                            if ($datos["autores"] == "") $datos["autores"] = "Indefinido";
                            break;
                    }
                    
                    $this->autorDAO->addAutor($datos);
                    $this->view->messageSuccess = "Autor(es) dado(s) de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de autor(es) <strong>".$ex->getMessage()."</strong>";
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
        $idAutor = $this->getParam('atr');
        
        $autor = $this->autorDAO->getAutorById($idAutor);
        
        $this->view->autor = $autor;
        
        if($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                
                $idAutor = $this->autorDAO->editarAutor($idAutor, $datos);
                $this->view->messageSuccess ="Autor: <strong>".$datos['nombres']."</strong> ha sido modificado";
                
            }catch (Exception $ex){
                $this->view->messageFail = "El autor: <strong>".$datos['nombres']."</strong> no ha sido modificado. Error: <strong>".$ex->getMessage()."<strong>";
            }
            
        }
    }


}








<?php

class Biblioteca_EditorialController extends Zend_Controller_Action
{

    private $editorialDAO = null;

    public function init()
    {
        /* Initialize action controller here */
         $dbAdapter = Zend_Registry::get("dbmodqueryb");
		 
		 $this->editorialDAO = new Biblioteca_DAO_Editorial($dbAdapter);
        
    }

    public function indexAction()
    {
        // action body
        
        $editoriales = $this->editorialDAO->getAllEditoriales();
        $editorialDAO   = $this->editorialDAO;
        $this->view->editoriales = $editoriales;
        $request = $this->getRequest();
        
        if($request->isPost()){
            $editoriales =$request->getPost();
            print_r($editoriales); print_r("<br />");
            
            
            foreach ($editoriales as $key => $value){
                if($value == "0"){
                    unset($editorial[$key]);
                }
            }
            
            $resources = $this->editorialDAO->getEditorialByParamas($editoriales);
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
        
        
        
        
        
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaEditorial;
        $this->view->formulario = $formulario;
		
		if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                	
					 $this->editorialDAO->agregarEditorial($datos);
                    $this->view->messageSuccess = "Editorial dada  de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de editorial <strong>".$ex->getMessage()."</strong>";
                }
            }
        }
    }

    public function adminAction()
    {
        // action body
        
        $request = $this->getRequest();
        $idEditorial = $this->getParam('ed');
        
        $editorialDAO = $this->editorialDAO;
        
        $editorial = $this->editorialDAO->getEditorialById($idEditorial);
        
        $this->view->editorial = $editorial;
        
        
        if($request->isPost()) {
            $datos = $request->getPost();
            
        try{
            
            $idEditorial = $this->editorialDAO->editarEditorial($idEditorial, $datos);
            $this->view->messageSuccess ="Editorial: <strong>".$datos['editorial']."</strong> ha sido modificado";
            
        }catch (Exception $ex){
            $this->view->messageFail = "La editorial: <strong>".$datos['editorial']."</strong> no ha sido modificada. Error: <strong>".$ex->getMessage()."<strong>";
        }
        
    }
 
    }
}






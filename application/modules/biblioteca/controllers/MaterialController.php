<?php

class Biblioteca_MaterialController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
        
        $this->materialDAO = new Biblioteca_Data_DAO_Material($dbAdapter);
    
    }

    public function indexAction()
    {
        // action body
        
        $materiales = $this->materialDAO->getAllMateriales();
        $this->view->materiales = $materiales;
   
       
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
       
       // $this->view->materiales = $meteriales;
        
        if ($request->isPost()) {
           
                $datos = $request->getPost();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    $this->materialDAO->addMaterial($datos);
                    $this->view->messageSuccess = "Colección dada de de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de colección <strong>".$ex->getMessage()."</strong>";
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
        $idMaterial = $this->getParam("mtrl");
        
        //print_r($idMaterial);
        
        $materiales = $this->materialDAO->getMaterialById($idMaterial);
        
        $this->view->materiales = $materiales;
    }


}








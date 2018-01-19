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
    }

    public function editaAction()
    {
        // action body
    }

    public function adminAction()
    {
        // action body
    }


}








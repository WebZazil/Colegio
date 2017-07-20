<?php

class Biblioteca_TemaController extends Zend_Controller_Action
{
	
	private $temaDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->temaDAO = new Biblioteca_Data_DAO_Tema($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        
        $temas = $this->temaDAO->getAllTemas();
		$this->view->temas = $temas;
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
    }


}








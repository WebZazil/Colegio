<?php

class Biblioteca_SubdivisionController extends Zend_Controller_Action
{
	
	private $subdivisionDAO;

    public function init()
    {
        /* Initialize action controller here */
        
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->subdivisionDAO = new Biblioteca_Data_DAO_Subdivision($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        
        $subdivisiones = $this->subdivisionDAO->getAllSubdivisiones();
		$this->view->subdivisiones = $subdivisiones;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaSubdivision;
        $this->view->formulario = $formulario;
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    $this->subdivisionDAO->addSubdivision($datos);
                    $this->view->messageSuccess = "Subdivisión dada de de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de subdivisión <strong>".$ex->getMessage()."</strong>";
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
        $idSubdivision = $this->getParam("sdv");
        
        $subdivisiones = $this->subdivisionDAO->getSubdivisionById($idSubdivision);
        
        $this->view->subdivisiones = $subdivisiones;
        
        
        if($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                
                $idSubdivision = $this->subdivisionDAO->editarSubdivision($idSubdivision, $datos);
                $this->view->messageSuccess ="Subdivision: <strong>".$datos['subdivision']."</strong> modificada";
                
            }catch (Exception $ex){
                $this->view->messageFail = "La subdivisión: <strong>".$datos['subdivision']."</strong> no ha sido modificada. Error: <strong>".$ex->getMessage()."<strong>";
            }
            
        }
        
    }


}








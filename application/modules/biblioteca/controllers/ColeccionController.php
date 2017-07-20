<?php

class Biblioteca_ColeccionController extends Zend_Controller_Action
{
	
    private $coleccionDAO;

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        $colecciones = $this->coleccionDAO->getAllColecciones();
		$this->view->colecciones = $colecciones;
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
    }


}








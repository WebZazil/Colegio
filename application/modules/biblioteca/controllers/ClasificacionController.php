<?php

class Biblioteca_ClasificacionController extends Zend_Controller_Action
{
	private $clasificacionDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        $clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
		$this->view->clasificaciones = $clasificaciones;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaClasificacion;
        $this->view->formulario = $formulario;
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    $this->clasificacionDAO->addClasificacion($data);
                    $this->view->messageSuccess = "Clasificación dada de de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de clasificación <strong>".$ex->getMessage()."</strong>";
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








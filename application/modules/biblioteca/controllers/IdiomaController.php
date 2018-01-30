<?php

class Biblioteca_IdiomaController extends Zend_Controller_Action
{
	
	private $idiomaDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->idiomaDAO = new Biblioteca_Data_DAO_Idioma($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        $idiomas = $this->idiomaDAO->getAllIdiomas();
        $this->view->idiomas = $idiomas;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaIdioma;
        $this->view->formulario = $formulario;
		
		if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                	
					 $this->idiomaDAO->agregarIdioma($datos);
                    $this->view->messageSuccess = "Idioma dado de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de editorial <strong>".$ex->getMessage()."</strong>";
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
        $idIdioma = $this->getParam('idm');
        
        //print_r($idClasificacion);
        
        $idiomas = $this->idiomaDAO->getIdiomaById($idIdioma);
        
        
        $this->view->idiomas = $idiomas;
    }


}








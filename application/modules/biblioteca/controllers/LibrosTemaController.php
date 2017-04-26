<?php

class Biblioteca_LibrosTemaController extends Zend_Controller_Action
{
	
	private $librosTemaDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->librosTemaDAO = new Biblioteca_DAO_LibrosTema();
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        
        	$request = $this->getRequest();
		
		$formulario = new Biblioteca_Form_AltaLibroTema();
		
		if ($request->isGet()) {
			$this->view->formulario = $formulario;
		}elseif ($request->isPost()) {
			if ($formulario->isValid($request->getPost())) {
				$datos = $formulario->getValues();
				
				$librosTema = new Biblioteca_Model_Librostema($datos);
				
				try{
					//$this->libroTemaDAO->agregarLibrosTema($librosTema);
					$this->librosTemaDAO->agregarLibrosTema($librosTema->getIdTema(), $librosTema->getIdsLibro());
					$this->view->messageSuccess = "Exito en la inserción";
				}catch(Exception $ex){
					$this->view->messageFail = "Fallo al insertar en la BD Error:".$ex->getMessage()."<strong>";
				}
			}
		}
    }


}




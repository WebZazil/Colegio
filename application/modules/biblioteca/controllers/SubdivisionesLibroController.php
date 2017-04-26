<?php

class Biblioteca_SubdivisionesLibroController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        
          $request = $this->getRequest();
		
		$formulario = new Biblioteca_Form_AltaSubdivisionesLibro();
		
		if ($request->isGet()) {
			
			$this->view->formulario = $formulario;
		}elseif ($request->isPost()) {
			if ($formulario->isValid($request->getPost())) {
				$datos = $formulario->getValues();
				
				$subdivisionesLibro = new Biblioteca_Model_SubdivisionesLibro($datos);
				
				try{
					$this->subdivisionesLibroDAO->agregarSubdivisionesLibro($subdivisionesLibro->getIdLibro(),$subdivisionesLibro->getIdsSubdivision());
					$this->view->messageSuccess = "Exito en la inserción";
				}catch(Exception $ex){
					$this->view->messageFail = "Fallo al insertar en la BD Error:".$ex->getMessage()."<strong>";
				}
			}
		}
    }


}




<?php

class Biblioteca_EjemplarController extends Zend_Controller_Action
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
		
		$formulario = new Biblioteca_Form_AltaEjemplar();
		
		if($request->isGet()){
			$this->view->formulario = $formulario;
			
		}elseif($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				
				$ejemplar= new Biblioteca_Model_Ejemplar($datos);
				
				try{
					$this->ejemplarDAO->agregarEjemplar($ejemplar);
					$this->view->messageSuccess ="El ejemplar: <strong>".$ejemplar->getIdRecurso()."</strong> ha sido agregado";
				}catch(Exception $ex){
					$this->view->messageFail = "El recurso: <strong>".$ejemplar->getIdRecurso()."</strong> no ha sido agregado. Error: <strong>".$ex->getMessage()."<strong>";
				}
			}
		}
    }


}




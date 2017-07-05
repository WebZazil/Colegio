<?php

class Biblioteca_RecursoController extends Zend_Controller_Action
{
	
	private $recursoDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->recursoDAO = new Biblioteca_DAO_Recurso($dbAdapter);    
	}

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
		$formulario = new Biblioteca_Form_Altarecurso;
		$this->view->formulario = $formulario;
		
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				$datos["creacion"] = date("Y-m-d h:i:s",time());

				$recurso = new Biblioteca_Model_Recurso($datos);
				
				try{
				  
					$this->recursoDAO->agregarRecurso($recurso);
					$this->view->messageSuccess ="El recurso: <strong>".$recurso->getTitulo()."</strong> ha sido agregado";
				}catch(Exception $ex){
					$this->view->messageFail = "El recurso: <strong>".$recurso->getTitulo()."</strong> no ha sido agregado. Error: <strong>".$ex->getMessage()."<strong>";
				}
			}
		}
        
    }


}




<?php

class Biblioteca_TemasSubdivisionController extends Zend_Controller_Action
{
	private $temasSubdivisionDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        $identity = $auth->getIdentity();
        
        $this->temasSubdivisionDAO = new Biblioteca_DAO_TemasSubdivision($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
		
		$formulario = new Biblioteca_Form_AltaTemasSubdivision();
		
		if ($request->isGet()) {
			
			$this->view->formulario = $formulario;
		}elseif ($request->isPost()) {
			if ($formulario->isValid($request->getPost())) {
				$datos = $formulario->getValues();
				
				$temasSubdivision = new Biblioteca_Model_TemasSubdivision($datos);
				
				try{
					$this->temasSubdivisionDAO->agregarTemasSubdivision($temasSubdivision->getIdSubDivision(),$temasSubdivision->getIdsTema());
					$this->view->messageSuccess = "Exito en la inserción";
				}catch(Exception $ex){
					$this->view->messageFail = "Fallo al insertar en la BD Error:".$ex->getMessage()."<strong>";
				}
			}
		}
    }


}




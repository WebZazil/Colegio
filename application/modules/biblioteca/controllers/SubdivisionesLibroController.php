<?php

class Biblioteca_SubdivisionesLibroController extends Zend_Controller_Action
{
	
	private $subdivisionesLibroDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        $identity = $auth->getIdentity();
        
        $this->subdivisionesLibroDAO = new Biblioteca_DAO_SubdivisionesLibro($identity['adapter']);
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
				    $this->subdivisionesLibroDAO->agregaSubdivisionesLibro($subdivisionesLibro->getIdRecurso(),$subdivisionesLibro->getIdsSubdivision());
			
					$this->view->messageSuccess = "Exito en la inserción";
				}catch(Exception $ex){
					$this->view->messageFail = "Fallo al insertar en la BD Error:".$ex->getMessage()."<strong>";
				}
			}
		}
    }


}




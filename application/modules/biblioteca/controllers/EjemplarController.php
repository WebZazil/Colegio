<?php

class Biblioteca_EjemplarController extends Zend_Controller_Action
{
    private $recursoDAO = null;
    private $ejemplarDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            ;
        }
        $identity = $auth->getIdentity();
        
        //$dbAdapter = Zend_Registry::get("dbmodqueryb");
        //$this->ejemplarDAO = new Biblioteca_DAO_Ejemplar($identity['adapter']);
        $this->ejemplarDAO = new Biblioteca_Data_DAO_Ejemplar($identity['adapter']);
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idRecurso = $this->getParam('rc');
        $this->view->recurso = $this->recursoDAO->getRecursoById($idRecurso);
        
        $ejemplarDAO = $this->ejemplarDAO;
		
		$this->view->tiposLibro = $ejemplarDAO->getAllRowsTiposLibro();
		$this->view->editoriales = $ejemplarDAO->getAllRowsEditoriales();
		$this->view->idiomas = $ejemplarDAO->getAllRowsIdiomas();
		$this->view->paises = $ejemplarDAO->getAllRowsPaises();
		$this->view->series = $ejemplarDAO->getAllRowsSeries();
		
		if($request->isPost()){
		    $datos = $request->getPost();
		    print_r($datos);
		    
		    try{
		        $this->view->messageSuccess ="Ejemplar ha sido agregado";
		    }catch(Exception $ex){
		        $this->view->messageFail = "Ejemplar no ha sido agregado.<br /> Error: <strong>".$ex->getMessage()."<strong>";
		    }
		}
    }

    public function adminAction()
    {
        // action body
    }

    public function ejemplaresAction()
    {
        // action body
        $idRecurso = $this->getParam('rc');
        $recurso = $this->recursoDAO->getRecursoById($idRecurso);
        $ejemplares = $this->ejemplarDAO->getObjectEjemplaresRecurso($idRecurso);
        
        $this->view->recurso = $recurso;
        $this->view->ejemplares = $ejemplares;
    }


}








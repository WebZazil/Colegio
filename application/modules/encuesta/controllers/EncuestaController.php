<?php

class Encuesta_EncuestaController extends Zend_Controller_Action
{
	private $encuestaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
        $this->encuestaDAO = new Encuesta_Data_DAO_Encuesta($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $encuestas = $this->encuestaDAO->getAllEncuesta();
        $this->view->encuestas = $encuestas;
    }

    public function adminAction()
    {
        // action body
        $idEncuesta = $this->getParam("id");
        if($this->encuestaDAO->existeEncuesta($idEncuesta)){
            //$encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
            $this->view->encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
        }else{
            throw new Exception("No existe la encuesta", 1);
        }
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $post = $request->getPost();
        //print_r($post);
        if ($request->isPost()) {
            $data = $request->getPost();
            
            $this->encuestaDAO->addEncuesta($data);
            $this->view->messageSuccess = "Encuesta dada de alta exitosamente!!";
        }
    }

    public function editaAction()
    {
        // action body
        $request = $this->getRequest();
        $id = $this->getParam("id");
        if($request->isPost()){
            $post = $request->getPost();
            //print_r($post);
            try{
                $this->encuestaDAO->editEncuesta($id, $post);
                $this->_helper->redirector->gotoSimple("admin", "encuestas", "encuesta", array("id" => $id));
            }catch(Exception $ex){
                
            }
        }else{
            //Redirect a www.site.com/encuesta/encuestas/
            $this->_helper->redirector->gotoSimple("index", "encuestas", "encuesta");
        }
    }

    public function configAction()
    {
        // action body
        $idEncuesta = $this->getParam("id");
        $encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
        $this->view->encuesta = $encuesta;
    }

    public function seccionesAction()
    {
        // action body
        $idEncuesta = $this->getParam("id");
        if (!is_null($idEncuesta)) {
            $this->view->encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
            $this->view->secciones = $this->encuestaDAO->getSeccionesByIdEncuesta($idEncuesta);
        }else{
            $this->_helper->redirector->gotoSimple("index", "encuestas", "encuesta");
        }
    }


}

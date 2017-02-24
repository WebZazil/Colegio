<?php

class Encuesta_EncuestaController extends Zend_Controller_Action
{
	private $encuestaDAO = null;
    private $identity = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $this->identity = $auth->getIdentity();
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($this->identity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $this->identity;
        //print_r($dataIdentity);
        //$encuestas = $this->encuestaDAO->getAllEncuestasByIdOrganizacion($dataIdentity["rol"]["idOrganizacion"]);
        $encuestas = $this->encuestaDAO->getAllEncuestas();
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
        if (is_null($this->identity)) {
            throw new Exception("No hay identidad asociada", 1);
        }
        $request = $this->getRequest();
        $post = $request->getPost();
        //print_r($post);
        if ($request->isPost()) {
            $encuesta = new Encuesta_Models_Encuesta($post);
            $this->encuestaDAO->addEncuesta($encuesta);
            //$this->encuestaDAO->asociarEncuestaAorganizacion($id, $this->identity["rol"]["idOrganizacion"]);
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
        if($this->encuestaDAO->existeEncuesta($idEncuesta)){
            //$encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
            $this->view->encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
        }else{
            throw new Exception("No existe la encuesta", 1);
        }
    }

    public function seccionesAction()
    {
        // action body
        $idEncuesta = $this->getParam("id");
        if (!is_null($idEncuesta)) {
            $this->view->encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
            $this->view->secciones = $this->encuestaDAO->obtenerSecciones($idEncuesta);
        }else{
            $this->_helper->redirector->gotoSimple("index", "encuestas", "encuesta");
        }
    }


}












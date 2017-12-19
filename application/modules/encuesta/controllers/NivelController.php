<?php

class Encuesta_NivelController extends Zend_Controller_Action
{
	private $nivelDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        if (!$auth->hasIdentity()) {
            $auth->clearIdentity();
            
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        
        $this->nivelDAO = new Encuesta_Data_DAO_NivelEducativo($identity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $this->view->niveles = $this->nivelDAO->getAllNivelesEducativos();
    }

    public function adminAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
		
		if($request->isPost()){
			$datos = $request->getPost();
            $datos["fecha"] = date("Y-m-d H:i:s", time());
            
            try{
                $this->nivelDAO->crearNivel($datos);
                $this->view->messageSuccess = "Nivel Educativo: <strong>" .$datos["nivelEducativo"]."</strong> creado exitosamente.";
            }catch(Exception $ex){
                $this->view->messageFail = $ex->getMessage();
            }
		}
    }


}






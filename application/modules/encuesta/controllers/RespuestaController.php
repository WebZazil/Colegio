<?php

class Encuesta_RespuestaController extends Zend_Controller_Action
{
	private $respuestaDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->respuestaDAO = new Encuesta_DAO_Respuesta;
    }

    public function indexAction()
    {
        // action body
    }

    public function adminAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
    }

    public function bajaAction()
    {
        // action body
        $idEncuesta = $this->getParam("idEncuesta");
		$idGrupo = $this->getParam("idGrupo");
		$idRegistro = $this->getParam("idRegistro");
		
		//print_r($this->getAllParams());
		
		$this->respuestaDAO->eliminarRespuestasGrupo($idEncuesta, $idGrupo,$idRegistro);
		$this->_helper->redirector->gotoSimple("index", "grupos", "encuesta", array("idGrupo" => $idGrupo));
    }

    public function editaAction()
    {
        // action body
    }


}










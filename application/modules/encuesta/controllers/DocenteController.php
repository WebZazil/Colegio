<?php

class Encuesta_DocenteController extends Zend_Controller_Action
{
	private $encuestaDAO = null;
    private $registroDAO = null;
    private $gruposDAO = null;
    private $asignacionGrupoDAO = null;
    private $materiaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($dataIdentity["adapter"]);
        $this->registroDAO = new Encuesta_DAO_Registro($dataIdentity["adapter"]);
        $this->gruposDAO = new Encuesta_DAO_Grupos($dataIdentity["adapter"]);
        $this->asignacionGrupoDAO = new Encuesta_DAO_AsignacionGrupo($dataIdentity["adapter"]);
        $this->materiaDAO = new Encuesta_DAO_Materia($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $this->view->docentes = $this->registroDAO->obtenerDocentes();
    }

    public function adminAction()
    {
        // action body
        $idDocente = $this->getParam("do");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            $this->registroDAO->editarRegistro($idDocente, $datos);
        }
        
        $docente = $this->registroDAO->obtenerRegistro($idDocente);
        $this->view->docente = $docente;
        
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos["fecha"] = date("Y-m-d H:i:s",time());
            $datos["tipo"] = "DO";
            print_r($datos);
            //$registro = new Encuesta_Model_Registro($datos);
            try{
                $this->registroDAO->crearRegistro($datos);
                $this->view->messageSuccess = "Docente: <strong>".$datos["apellidos"].", ".$datos["nombres"]."</strong> dado de alta exitosamente";
            }catch(Exception $ex){
                $this->view->messageFail = $ex->getMessage();
            }
            /**/
        }
    }

    public function evaluacionesAction()
    {
        // action body
        $idDocente = $this->getParam("idDocente");
        $docente = $this->registroDAO->obtenerRegistro($idDocente);
        
        $asignaciones = $this->asignacionGrupoDAO->obtenerAsignacionesDocente($idDocente);
        //print_r($asignaciones);
        $this->view->docente = $docente;
        $this->view->asignaciones = $asignaciones;
        
        $this->view->encuestaDAO = $this->encuestaDAO;
        $this->view->materiaDAO = $this->materiaDAO;
        $this->view->gruposDAO = $this->gruposDAO;
        $this->view->asignacionDAO = $this->asignacionGrupoDAO;
    }


}








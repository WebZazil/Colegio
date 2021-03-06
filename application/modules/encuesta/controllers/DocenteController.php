<?php

class Encuesta_DocenteController extends Zend_Controller_Action
{
	private $encuestaDAO = null;
    //private $registroDAO = null;
    private $gruposDAO = null;
    private $asignacionGrupoDAO = null;
    private $materiaDAO = null;
    private $docenteDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($identity["adapter"]);
        //$this->registroDAO = new Encuesta_DAO_Registro($identity["adapter"]);
        $this->gruposDAO = new Encuesta_DAO_Grupos($identity["adapter"]);
        //$this->asignacionGrupoDAO = new Encuesta_DAO_AsignacionGrupo($identity["adapter"]);
        $this->materiaDAO = new Encuesta_DAO_Materia($identity["adapter"]);
        
        $this->docenteDAO = new Encuesta_Data_DAO_Docente($identity['adapter']);
        $this->asignacionGrupoDAO = new Encuesta_Data_DAO_AsignacionGrupo($identity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $this->view->docentes = $this->docenteDAO->getAllDocentes();
    }

    public function adminAction()
    {
        // action body
        $idDocente = $this->getParam("do");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            $this->docenteDAO->updateDocente($idDocente,$datos);
        }
        
        $docente = $this->docenteDAO->getDocenteById($idDocente);
        $this->view->docente = $docente;
        
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos["creacion"] = date("Y-m-d H:i:s",time());
            $datos["tipo"] = "DO";
            //print_r($datos);
            //$registro = new Encuesta_Model_Registro($datos);
            try{
                $this->docenteDAO->addDocente($datos);
                
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
        $idDocente = $this->getParam("do");
        $docente = $this->docenteDAO->getDocenteById($idDocente);
        
        $asignaciones = $this->asignacionGrupoDAO->getAsignacionesByIdDocente($idDocente);
        //print_r($asignaciones);
        $this->view->docente = $docente;
        $this->view->asignaciones = $asignaciones;
        
        $this->view->encuestaDAO = $this->encuestaDAO;
        $this->view->materiaDAO = $this->materiaDAO;
        $this->view->gruposDAO = $this->gruposDAO;
        $this->view->asignacionDAO = $this->asignacionGrupoDAO;
    }


}








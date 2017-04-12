<?php

class Encuesta_ReporteController extends Zend_Controller_Action
{

    private $gruposDAO = null;

    private $grupoDAO = null;

    private $gradoDAO = null;

    private $cicloDAO = null;

    private $nivelDAO = null;

    private $encuestaDAO = null;

    private $seccionDAO = null;

    private $generador = null;

    private $preguntaDAO = null;

    private $registroDAO = null;

    private $respuestaDAO = null;

    private $preferenciaDAO = null;

    private $reporteDAO = null;

    private $materiaDAO = null;

    private $reporter = null;
    
    private $reporteador = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        //print_r($dataIdentity);
        
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($dataIdentity["adapter"]);
        $this->seccionDAO = new Encuesta_DAO_Seccion($dataIdentity["adapter"]);
        $this->grupoDAO = new Encuesta_DAO_Grupo($dataIdentity["adapter"]);
        $this->preguntaDAO = new Encuesta_DAO_Pregunta($dataIdentity["adapter"]);
        
        $this->registroDAO = new Encuesta_DAO_Registro($dataIdentity["adapter"]);
        
        $this->gradoDAO = new Encuesta_DAO_Grado($dataIdentity["adapter"]);
        $this->nivelDAO = new Encuesta_DAO_Nivel($dataIdentity["adapter"]);
        $this->cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
        $this->gruposDAO = new Encuesta_DAO_Grupos($dataIdentity["adapter"]);
        
        $this->respuestaDAO = new Encuesta_DAO_Respuesta($dataIdentity["adapter"]);
        $this->preferenciaDAO = new Encuesta_DAO_Preferencia($dataIdentity["adapter"]);
        
        $this->reporteDAO = new Encuesta_DAO_Reporte($dataIdentity["adapter"]);
        $this->generador = new Encuesta_Util_Generator(($dataIdentity["adapter"]));
        $this->materiaDAO = new Encuesta_DAO_Materia($dataIdentity["adapter"]);
        $this->reporter = new Encuesta_Util_Reporter($dataIdentity["adapter"]);
        $this->reporteador = new Encuesta_Util_Reporteador($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
    }

    public function abiertasAction()
    {
        // action body
    }

    public function consultaAction()
    {
        // action body
        $this->view->docentes = $this->registroDAO->obtenerDocentes();
    }

    public function descargaAction()
    {
        // action body
        $idReporte = $this->getParam("idReporte");
        $reporteDAO = $this->reporteDAO;
        $reporte = $reporteDAO->obtenerReporte($idReporte);
        
        $this->view->reporte = $reporte;
    }

    public function generalAction()
    {
        // action body
    }

    public function grupalAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idEncuesta = $this->getParam("idEncuesta");
        $idAsignacion = $this->getParam("idAsignacion");
        //$asignacion = $this->gruposDAO->obtenerAsignacion($idAsignacion);
        $this->view->asignacion = $this->gruposDAO->obtenerAsignacion($idAsignacion);
        $this->view->encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);//->obtenerEncuesta($idEncuesta);
        
        $this->view->encuestaDAO = $this->encuestaDAO;
        $this->view->seccionDAO = $this->seccionDAO;
        $this->view->grupoDAO = $this->grupoDAO;
        $this->view->preguntaDAO = $this->preguntaDAO;
        
        $this->view->registroDAO = $this->registroDAO;
        
        $this->view->reporteDAO = $this->reporteDAO;
        $this->view->materiaDAO = $this->materiaDAO;
        $this->view->gruposDAO = $this->gruposDAO;
        $this->view->preferenciaDAO = $this->preferenciaDAO;
        $this->view->nivelDAO = $this->nivelDAO;
        $this->view->gradoDAO = $this->gradoDAO;
        $this->view->reporter = $this->reporter;
    }

    public function docenteAction()
    {
        // action body
    }

    public function repgrasAction()
    {
        // action body
        $idGrupo = $this->getParam("gpo");
        $idEncuesta = $this->getParam("ev");
        $idAsignacion = $this->getParam("as");
        
        $this->reporteador->generarReporteGrupalAsignacion($idGrupo, $idAsignacion, $idEncuesta);
    }


}
















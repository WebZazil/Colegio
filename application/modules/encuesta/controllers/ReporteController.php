<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
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
        if (!$auth->hasIdentity()) {
            $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
            
            $this->serviceLogin = new Encuesta_Service_Login();
            $testConnector = $this->serviceLogin->getTestConnection($testData);
        }else {
            $identity = $auth->getIdentity();
            $testConnector = $identity['adapter'];
        }
        
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($testConnector);
        $this->seccionDAO = new Encuesta_DAO_Seccion($testConnector);
        $this->grupoDAO = new Encuesta_DAO_Grupo($testConnector);
        $this->preguntaDAO = new Encuesta_DAO_Pregunta($testConnector);
        
        $this->registroDAO = new Encuesta_DAO_Registro($testConnector);
        
        $this->gradoDAO = new Encuesta_DAO_Grado($testConnector);
        $this->nivelDAO = new Encuesta_DAO_Nivel($testConnector);
        $this->cicloDAO = new Encuesta_DAO_Ciclo($testConnector);
        $this->gruposDAO = new Encuesta_DAO_Grupos($testConnector);
        
        $this->respuestaDAO = new Encuesta_DAO_Respuesta($testConnector);
        $this->preferenciaDAO = new Encuesta_DAO_Preferencia($testConnector);
        
        
        $this->generador = new Encuesta_Util_Generator($testConnector);
        $this->materiaDAO = new Encuesta_DAO_Materia($testConnector);
        $this->reporter = new Encuesta_Util_Reporter($testConnector);
        $this->reporteador = new Encuesta_Util_Reporteador($testConnector);
        
        
        $this->reporteDAO = new Encuesta_DAO_Reporte($testConnector);
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
        $idReporte = $this->getParam("rpt");
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

    public function desrAction()
    {
        // action body
        $this->_helper->layout->disableLayout();
        
        $idReporte = $this->getParam("idReporte");
        $reporteDAO = $this->reporteDAO;
        $reporte = $reporteDAO->obtenerReporte($idReporte);
        
        $this->view->reporte = $reporte;
    }

    public function desrgAction()
    {
        // action body
        $this->_helper->layout->disableLayout();
        
        $idReporte = $this->getParam("idReporte");
        $reporteDAO = $this->reporteDAO;
        $reporte = $reporteDAO->obtenerReporteGeneral($idReporte);
        
        $this->view->reporte = $reporte;
    }


}




















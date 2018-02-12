<?php

class Encuesta_IndexController extends Zend_Controller_Action
{

    private $service = null;
    private $loginDAO = null;
    private $identity = null;
    private $cicloDAO = null;
    private $docenteDAO = null;
    private $nivelDAO = null;
    private $encuestaDAO = null;
    private $materiaDAO = null;
    private $registroDAO = null;
    private $evaluacionDAO = null;
    private $asignacionDAO = null;
    private $generador = null;
    private $grupoDAO = null;
    private $gradoDAO = null;
    
    // =========================================== improvements Noviembre 2017
    private $testConnector;
    private $serviceLogin;

    public function init()
    {
        /* Initialize action controller here */
        $this->serviceLogin = new Encuesta_Service_Login();
        
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $this->testConnector = $this->serviceLogin->getTestConnection($testData);
        
        $this->_helper->layout->setLayout('homeEncuesta');
    }

    public function indexAction()
    {
        // action body
        $cicloDAO = new Encuesta_DAO_Ciclo($this->testConnector);
        $nivelDAO = new Encuesta_DAO_Nivel($this->testConnector);
        
        $ciclos = $cicloDAO->getAllCiclos();
        $niveles = $nivelDAO->obtenerNiveles();
        
        $this->view->ciclosEscolares = $ciclos;
        $this->view->nivelesEscolares = $niveles;
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        $serviceLogin = $this->serviceLogin;
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            // @TODO hardcoded claveOrganizacion y tipoModulo
            try {
                $serviceLogin->simpleLogin($datos, 'colsagcor16', 'MOD_ENCUESTA');
                $this->_helper->redirector->gotoSimple("index", "dashboard", "encuesta");
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		
		$this->_helper->redirector->gotoSimple("index", "index", "encuesta");
    }

    public function evalsAction()
    {
        // action body
        $idConjunto = $this->getParam("conjunto");
        $idEvaluacion = $this->getParam("evaluacion");
        
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        $encuesta = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        $evaluadores = $this->evaluacionDAO->getEvaluadoresByIdConjunto($idConjunto);
        
        $this->view->conjunto = $conjunto;
        $this->view->evaluacion = $encuesta;
        $this->view->evaluadores = $evaluadores;
    }

    public function conjuntosAction()
    {
        // action body
        $idGrupoEscolar = $this->getParam("idGrupo");
        $grupo = $this->grupoDAO->obtenerGrupo($idGrupoEscolar);
        $conjuntos = $this->evaluacionDAO->getConjuntosByIdGrupoEscolar($idGrupoEscolar);
        $this->view->grupo = $grupo;
        $this->view->conjuntos = $conjuntos;
    }

    public function asignsAction()
    {
        // action body
        $idConjunto = $this->getParam("conjunto");
        $idEvaluacion = $this->getParam("evaluacion");
        $idEvaluador = $this->getParam("evaluador");
        
        $encuesta = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        $evaluador = $this->evaluacionDAO->getEvaluadorById($idEvaluador);
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        
        $this->view->conjunto = $conjunto;
        $this->view->evaluador = $evaluador;
        $this->view->encuesta = $encuesta;
        
        //$asignacionesGrupo =  $this->asignacionDAO->obtenerAsignacionesGrupo($conjunto["idGrupoEscolar"]);
        $asignacionesConjunto = $this->evaluacionDAO->getAsignacionesByIdConjuntoAndIdEvaluacion($idConjunto, $idEvaluacion);
        // disponibles = grupo - conjunto i.e. todas - asignadas.
        
        //print_r($asignacionesConjunto);
        //$asignacionesDisponibles = array();
        //print_r($asignacionesGrupo);
        /*
        foreach ($asignacionesGrupo as $asignacionG) {
            //print_r($asignacionG);
            foreach ($asignacionesConjunto as $asignacionC) {
                if($asignacionC["idAsignacionGrupo"] == $asignacionG["idAsignacionGrupo"]){
                    //print_r("Relacionada<br />");
                    
                }else{
                    //print_r("No Relacionada<br />");
                    $asignacionesDisponibles[] = $asignacionG;
                }
            }
        }*/
        // 
        $asignacionesC = array();
        //$asignacionesD = array();
        
        foreach ($asignacionesConjunto as $asignacion) {
            $obj = array();
            $obj["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
            $obj["docente"] = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
            $asignacionesC[$asignacion["idAsignacionGrupo"]] = $obj;
        }
        /*
        foreach ($asignacionesDisponibles as $asignacion) {
            $obj = array();
            $obj["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
            $obj["docente"] = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
            $asignacionesD[$asignacion["idAsignacionGrupo"]] = $obj;
        }*/
        
        //$this->view->asignacionesDisponibles = $asignacionesD;
        $this->view->asignacionesConjunto = $asignacionesC;
        $this->view->evaluacionDAO = $this->evaluacionDAO;
    }

    public function evaluarAction()
    {
        // action body
        $request = $this->getRequest();
        $idConjunto = $this->getParam("conjunto");
        $idEvaluador = $this->getParam("evaluador");
        $idEncuesta = $this->getParam("evaluacion");
        $idAsignacion = $this->getParam("asignacion");
        
		$asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
		$evaluador = $this->evaluacionDAO->getEvaluadorById($idEvaluador);
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
		$evaluacion = $this->encuestaDAO->getEncuestaById($idEncuesta);
        
        $grupo = $this->grupoDAO->obtenerGrupo($asignacion["idGrupoEscolar"]);
        $materia = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
        $docente = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"]);
        
        $grado = $this->gradoDAO->getGradoById($grupo->getIdGrado());
        $nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
        
        $this->view->grupo = $grupo;
        $this->view->materia = $materia;
        $this->view->docente = $docente;
        
        $this->view->grado = $grado;
        $this->view->nivel = $nivel;
		
		$this->view->asignacion = $asignacion;
		$this->view->evaluador = $evaluador;
		$this->view->conjunto = $conjunto;
		$this->view->evaluacion = $evaluacion;
        
        //print_r($asignacion);
        //print_r($grupo->getIdGrado());
        
        $generador = $this->generador;
        $form = new Encuesta_Form_TestForm;
        $this->view->form = $form;
        
        $formulario = $generador->generarFormulario($idEncuesta, $idAsignacion);
        
        if($request->isGet()){
            $this->view->formulario = $formulario;
        }
        
        if ($request->isPost()) {
            $post = $request->getPost();
            print_r($post);
            
            try{
                //$generador->procesarFormulario($idEncuesta,$idAsignacion,$post);
                $this->view->messageSuccess = "Encuesta registrada correctamente";
            }catch(Exception $ex){
                $this->view->messageFail = "Error al Registrar la encuesta: " . $ex->getMessage();
            }
            /**/
        }
    }

    public function evaluadoresAction()
    {
        // action body
        $grupo = $this->getParam("grupo");
		$objGrupo = $this->grupoDAO->obtenerGrupo($grupo);
        //Obtenemos todos los evaluadores del grupo
        //print_r($grupo);
		$conjuntos = $this->evaluacionDAO->getEvaluadoresGrupo($grupo);
		$this->view->conjuntos = $conjuntos;
		$this->view->grupo = $objGrupo;
    }

    public function evaluacionesAction() {
        // action body
        $conjunto = $this->getParam("conjunto");
        $evaluador = $this->getParam("evaluador");
        
        $evaluaciones = $this->evaluacionDAO->getEvaluacionesByIdConjunto($conjunto);
        
        $objConjunto = $this->evaluacionDAO->getConjuntoById($conjunto);
        $objEvaluador = $this->evaluacionDAO->getEvaluadorById($evaluador);
        
        $this->view->evaluaciones = $evaluaciones;
        $this->view->conjunto = $objConjunto;
        $this->view->evaluador = $objEvaluador;
    }


}





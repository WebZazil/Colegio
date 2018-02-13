<?php
/**
 * @author EnginnerRodriguez
 * 
 */

class Encuesta_EvaluacionController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;
    private $materiaDAO = null;
    private $grupoDAO = null;
    private $asignacionDAO = null;
    private $conjuntoDAO = null;
    private $serviceLogin = null;
    private $testConnector = null;
    
    private $nivelDAO;
    private $gradoDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeEncuesta');
        
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
            $this->serviceLogin = new Encuesta_Service_Login();
            $this->testConnector = $this->serviceLogin->getTestConnection($testData);
        }
        
        $identity = $auth->getIdentity();
        
        $this->conjuntoDAO = new Encuesta_Data_DAO_ConjuntoEvaluador($this->testConnector);
        $this->evaluacionDAO = new Encuesta_Data_DAO_Evaluacion($this->testConnector);
        $this->materiaDAO = new Encuesta_Data_DAO_Materia($this->testConnector);
        $this->grupoDAO = new Encuesta_Data_DAO_GrupoEscolar($this->testConnector);
        $this->asignacionDAO = new Encuesta_Data_DAO_AsignacionGrupo($this->testConnector);
        
        $this->nivelDAO = new Encuesta_Data_DAO_NivelEducativo($this->testConnector);
        $this->gradoDAO = new Encuesta_Data_DAO_GradoEducativo($this->testConnector);
    }

    public function indexAction()
    {
        // action body
        $evals = $this->evaluacionDAO->getAllEvaluaciones();
        //print_r($evals);
    }

    public function evalsAction()
    {
        // action body
        $idAsignacion = $this->getParam('as');
        $idConjunto = $this->getParam('co');
        $idEncuesta = $this->getParam('ev');
        
        if ($this->isValidParam($idAsignacion) &&
                $this->isValidParam($idConjunto) &&
                    $this->isValidParam($idEncuesta) ) {
            //print_r('Parametros Validos');
            
            $en = $this->evaluacionDAO->getEncuestaById($idEncuesta);
            $obj = $this->asignacionDAO->getObjByIdAsignacion($idAsignacion);
            
            
            $this->view->en = $en;
            $this->view->obj = $obj;
            
        }else{
            print_r('Parametros Invalidos');
        }
    }

    public function evaluadoresAction()
    {
        $idGrupo = $this->getParam("gpo");
        $grupo = $this->grupoDAO->getGrupoById($idGrupo);
        $conjuntos = $this->conjuntoDAO->getAllRowsConjuntosByIdGrupoEscolar($idGrupo);
        
        $objs = array();
        
        foreach ($conjuntos as $conjunto){
            $objs[] = $this->conjuntoDAO->getObjectConjuntoById($conjunto['idConjuntoEvaluador']);
        }
        
        $this->view->grupo = $grupo;
        $this->view->evaluadores = $objs;
    }

    private function isValidParam($param)
    {
        if (is_null($param) || $param == "") {
            return false;
        } else {
            return true;
        }
    }

    public function evaluacionesAction()
    {
        // action body
        $idEvaluacion = $this->getParam('ev');
        $idConjunto = $this->getParam('co');
        $idEvaluador = $this->getParam('er');
        
        //$evaluacion = $this->evaluacionDAO->getEncuestaById($idEvaluacion);
        $conjunto = $this->conjuntoDAO->getObjectConjuntoById($idConjunto);
        $evaluaciones = $conjunto['evaluaciones'];
        $evaluacion = "";
        foreach ($evaluaciones as $ev){
            if($ev['idEncuesta'] == $idEvaluacion){
                $evaluacion = $ev;
            }
        }
        
        $evaluador = "";
        $evaluadores = $conjunto['evaluadores'];
        foreach ($evaluadores as $ev){
            if($ev['idEvaluador'] == $idEvaluador){
                $evaluador = $ev;
            }
        }
        $objs = array();
        $asignaciones = $this->conjuntoDAO->getAsignacionesByIdConjuntoAndIdEvaluacion($idConjunto, $idEvaluacion);
        foreach ($asignaciones as $asignacion){
            $obj = $this->asignacionDAO->getObjByIdAsignacion($asignacion['idAsignacionGrupo']);
            $objs[] = $obj;
        }
        
        $this->view->evaluacion = $evaluacion;
        $this->view->conjunto = $conjunto;
        $this->view->evaluador = $evaluador;
        $this->view->asignaciones = $objs;
    }

    public function evaluarAction() {
        $idConjunto = $this->getParam('co');
        $idEvaluacion = $this->getParam('ev');
        $idAsignacion = $this->getParam('as');
        $idEvaluador = $this->getParam('er');
        
        $conjunto = $this->conjuntoDAO->getObjectConjuntoById($idConjunto); 
        $objAsignacion = $this->asignacionDAO->getObjByIdAsignacion($idAsignacion);
        $evaluador = $this->evaluacionDAO->getEvaluadorById($idEvaluador);
        $encuesta = $this->evaluacionDAO->getEncuestaById($idEvaluacion);
        
        $grupo = $objAsignacion['ge'];
        $grado = $this->gradoDAO->getGradoEducativoById($grupo['idGradoEducativo']);
        $nivel = $this->nivelDAO->getNivelEducativoById($grado['idNivelEducativo']);
        //print_r($encuesta);
        $this->view->conjunto = $conjunto;
        $this->view->evaluador = $evaluador;
        $this->view->asignacion = $objAsignacion;
        $this->view->evaluacion = $encuesta;
        $this->view->grado = $grado;
        $this->view->nivel = $nivel;
        
    }
}


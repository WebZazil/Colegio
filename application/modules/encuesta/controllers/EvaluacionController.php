<?php

class Encuesta_EvaluacionController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;
    private $materiaDAO = null;
    private $grupoDAO = null;
    private $asignacionDAO = null;
    
    private $serviceLogin = null;
    private $testConnector = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            print_r($auth->getIdentity());
        }
        
        $identity = $auth->getIdentity();
        
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $this->serviceLogin = new Encuesta_Service_Login();
        $this->testConnector = $this->serviceLogin->getTestConnection($testData);
        
        $this->evaluacionDAO = new Encuesta_Data_DAO_Evaluacion($this->testConnector);
        $this->materiaDAO = new Encuesta_Data_DAO_Materia($this->testConnector);
        $this->grupoDAO = new Encuesta_Data_DAO_Grupo($this->testConnector);
        $this->asignacionDAO = new Encuesta_Data_DAO_Asignacion($this->testConnector);
        
        $this->_helper->layout->setLayout('homeEncuesta');
    }

    public function indexAction() {
        // action body
        $evals = $this->evaluacionDAO->getAllEvaluaciones();
        print_r($evals);
    }

    public function evalsAction() {
        // action body
        $idAsignacion = $this->getParam('as');
        $idConjunto = $this->getParam('co');
        $idEncuesta = $this->getParam('ev');
        // Test con as=
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
    
    public function evaluadoresAction() {
        $idGrupo = $this->getParam("grupo");
        $grupo = $this->grupoDAO->getGrupoById($idGrupo);
        $conjuntos = $this->grupoDAO->getConjuntosByIdGrupoEscolar($idGrupo);
        //$objGrupo = $this->grupoDAO->obtenerGrupo($grupo);
        //Obtenemos todos los evaluadores del grupo
        //print_r($grupo);
        //$conjuntos = $this->evaluacionDAO->getEvaluadoresGrupo($grupo);
        $this->view->conjuntos = $conjuntos;
        $this->view->grupo = $grupo;
        $this->view->evaluacionDAO = $this->evaluacionDAO;
    }
    
    
    private function isValidParam($param) {
        if (is_null($param) || $param == "") {
            return false;
        } else {
            return true;
        }
    }
    
}



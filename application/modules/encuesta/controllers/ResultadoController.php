<?php

class Encuesta_ResultadoController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;
    private $grupoDAO = null;
    private $cicloDAO = null;
    private $encuestaDAO = null;
    private $materiaDAO = null;
    private $asignacionDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodquery");
        
        $this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
        $this->grupoDAO = new Encuesta_DAO_Grupos($dbAdapter);
        $this->cicloDAO = new Encuesta_DAO_Ciclo($dbAdapter);
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($dbAdapter);
        
        $this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
        $this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
        $this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($dbAdapter);
    }

    public function indexAction()
    {
        // action body
    }

    public function graficaAction()
    {
        // action body
    }

    public function resconAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
        $idEvaluacion = $this->getParam("ev");
        $idAsignacion = $this->getParam("as");
        
        $evaluacion = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        $asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        
        $this->view->conjunto = $conjunto;
        $this->view->asignacion = $asignacion;
        $this->view->evaluacion = $evaluacion;
        
        
    }

    public function evalsAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
    }

    public function evalscoAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        //$evaluacionesConjunto = $this->evaluacionDAO->getEvaluacionesByIdConjunto($idConjunto);
        $asignacionesConjunto = $this->evaluacionDAO->getAsignacionesByIdConjunto($idConjunto);
        //print_r($asignacionesConjunto);
        $this->view->conjunto = $conjunto;
        $this->view->asignacionesConjunto = $asignacionesConjunto;
        $this->view->encuestaDAO = $this->encuestaDAO;
        $this->view->registroDAO = $this->registroDAO;
        $this->view->materiaDAO = $this->materiaDAO;
    }

    public function evalsgrAction()
    {
        // action body
    }

    public function conjuntosAction()
    {
        // action body
        $conjuntos = $this->evaluacionDAO->getAllConjuntos();
        $this->view->conjuntos = $conjuntos;
    }

    public function gruposAction()
    {
        // action body
        $cicloDAO = $this->cicloDAO;
        $cicloActual = $cicloDAO->getCurrentCiclo();
        $grupos = $this->grupoDAO->getAllGruposByIdCicloEscolar($cicloActual->getIdCiclo());
        $this->view->grupos = $grupos; 
    }


}

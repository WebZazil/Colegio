<?php

class Encuesta_EvaluacionController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;
    private $materiaDAO = null;
    private $grupoDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            print_r($auth->getIdentity());
        }
        
        $identity = $auth->getIdentity();
        
        $this->evaluacionDAO = new Encuesta_Data_DAO_Evaluacion($identity['adapter']);
        $this->materiaDAO = new Encuesta_Data_DAO_Materia($identity['adapter']);
        $this->grupoDAO = new Encuesta_Data_DAO_Grupo($identity['adapter']);
        
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
        $idEvaluacion = $this->getParam('ev');
        // Test con as=
        if ($this->isValidParam($idAsignacion) &&
                $this->isValidParam($idConjunto) &&
                    $this->isValidParam($idEvaluacion) ) {
            print_r('Parametros Validos');
            //$idAsignacion
            
            
            
            // 
        }else{
            print_r('Parametros Invalidos');
        }
    }
    
    
    private function isValidParam($param) {
        if (is_null($param) || $param == "") {
            return false;
        } else {
            return true;
        }
    }
    
}



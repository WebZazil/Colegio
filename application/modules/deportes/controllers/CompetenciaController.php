<?php
/**
 * @author EnginnerRodriguez
 */


class Deportes_CompetenciaController extends Zend_Controller_Action
{
    private $deporteDAO = null;
    private $competenciaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (! $auth->hasIdentity()) {
            ;
        }
        
        $identity = $auth->getIdentity();
        
        $this->competenciaDAO = new Deportes_Data_DAO_Competencia($identity['adapter']);
        $this->deporteDAO = new Deportes_Data_DAO_Deporte($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $this->view->deportes = $this->deporteDAO->getAllDeportes();
        $this->view->estatusCompetencia = $this->competenciaDAO->getAllEstatusCompetencias();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['puntajeA'] = 0;
            $datos['puntajeB'] = 0;
            $datos['idEquipoGanador'] = 1;
            $datos['creacion'] = date('Y-m-d H:i:s',time());
            //print_r($datos);
            
            try {
                $this->competenciaDAO->addCompetencia($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
            
        }
        
    }

    public function adminestAction()
    {
        // action body
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['creacion'] = date('Y-m-d H:i:s');
            
            print_r($datos);
            try {
                $this->competenciaDAO->addEstatusCompetencias($datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->estatusCompetencia = $this->competenciaDAO->getAllEstatusCompetencias();
    }

    public function configestAction()
    {
        // action body
        $request = $this->getRequest();
        $idEstatusCompetencia = $this->getParam('est');
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            
            print_r($datos);
            
            try {
                $this->competenciaDAO->editEstatusCompetencia($idEstatusCompetencia, $datos);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        
        $this->view->estatus = $this->competenciaDAO->getEstatusCompetenciaById($idEstatusCompetencia);
    }


}


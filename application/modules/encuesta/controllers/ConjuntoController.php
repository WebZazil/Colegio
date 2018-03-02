<?php

class Encuesta_ConjuntoController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;
    private $asignacionDAO = null;
    private $encuestaDAO = null;
    private $grupoDAO = null;
    private $registroDAO = null;
    private $materiaDAO = null;
    
    private $conjuntoDAO = null;
    private $docenteDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
        //$dbAdapter = Zend_Registry::get("dbmodquery");
		
		$this->evaluacionDAO = new Encuesta_DAO_Evaluacion($identity['adapter']);
		
		$this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($identity['adapter']);
		$this->encuestaDAO = new Encuesta_DAO_Encuesta($identity['adapter']);
		
		$this->grupoDAO = new Encuesta_DAO_Grupos($identity['adapter']);
		$this->registroDAO = new Encuesta_DAO_Registro($identity['adapter']);
		$this->materiaDAO = new Encuesta_DAO_Materia($identity['adapter']);
		
		$this->conjuntoDAO = new Encuesta_Data_DAO_ConjuntoEvaluador($identity['adapter']);
		$this->docenteDAO = new Encuesta_Data_DAO_Docente($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $this->view->conjuntos = $this->evaluacionDAO->getAllConjuntos();
        $this->view->grupoDAO = $this->grupoDAO;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $formulario = new Encuesta_Form_AltaConjunto;
		$this->view->formulario = $formulario;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				
				//print_r($datos);
				try{
					$this->evaluacionDAO->addConjuntoEvaluador($datos);
					$this->view->messageSuccess = "El conjunto <strong>".$datos["nombre"]."</strong> ha sido creado correctamente.";
				}catch(Exception $ex){
					$this->view->messageFail = "Ha ocurrido un error: <strong>".$ex->getMessage()."</strong>";
				}
				/**/
			}
		}
		
    }

    public function adminAction()
    {
        // configuramos conjunto
        $request = $this->getRequest();
        $idConjunto = $this->getParam("co");
        
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        $evaluaciones = $this->evaluacionDAO->getEvaluacionesByIdConjunto($idConjunto);
        
        // Evaluaciones del conjunto
        $this->view->conjunto = $conjunto;
        $this->view->conjuntos = $this->evaluacionDAO->getConjuntosByIdGrupoEscolar($conjunto["idGrupoEscolar"]);
        
        if($request->isPost()){
            $datos = $request->getpost();
            print_r($datos);
            
            
        }
        
        
    }

    public function evaluadoresAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
        $conjunto = $this->conjuntoDAO->getRowConjuntoById($idConjunto);
		// $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
		$evaluadores = $this->evaluacionDAO->getEvaluadoresByIdConjunto($idConjunto);
		$this->view->evaluadores = $evaluadores;
		$this->view->conjunto = $conjunto;
		//$this->evaluacionDAO->getEvaluadoresByIdConjunto; // Evaluadores registrados en un conjunto
        $this->view->evaluacionDAO = $this->evaluacionDAO;
    }

    /**
     * $idConjunto
     * $idEncuesta
     */
    public function agregarevalAction()
    {
        // action body
        $request = $this->getRequest();
		$idConjunto = $this->getParam("idConjunto");
		if($request->isPost()){
			$datos = $request->getPost();
			//print_r($datos);
			try{
				$this->evaluacionDAO->asociarEvaluacionAConjunto($idConjunto, $datos["idEncuesta"]);
				$this->_helper->redirector->gotoSimple("evaluaciones", "conjunto", "encuesta", array("idConjunto"=>$idConjunto));
			}catch(Exception $ex){
				print_r($ex->getMessage());
			}
		}
        
    }

    public function agregarasignAction()
    {
        // action body
        $request = $this->getRequest();
		
		$idConjunto = $this->getParam("co");
        $idEvaluacion = $this->getParam("ev");
        $idAsignacion = $this->getParam("as");
        
        try{
            $this->evaluacionDAO->asociarAsignacionAConjunto($idConjunto, $idEvaluacion, $idAsignacion);
            $this->_helper->redirector->gotoSimple("asignaciones", "conjunto", "encuesta", array("idConjunto"=>$idConjunto));
        }catch(Exception $ex){
            print_r($ex->getMessage());
        }
    }

    public function evaluacionesAction()
    {
        // action body
        $request = $this->getRequest();
        $idConjunto = $this->getParam("idConjunto");
		$conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
		$evaluaciones = $this->evaluacionDAO->getEvaluacionesByIdConjunto($idConjunto);
		$encuestas = $this->encuestaDAO->getAllEncuestas();
		
		$this->view->conjunto = $conjunto;
		$this->view->encuestas = $encuestas;
		$this->view->evaluaciones = $evaluaciones;
    }

    public function evalsAction()
    {
        // action body
        $idConjunto = $this->getParam("idConjunto");
		$idEvaluacion = $this->getParam("idEvaluacion");
		$conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
		$encuesta = $this->encuestaDAO->getEncuestaById($idEvaluacion);
		$evaluadores = $this->evaluacionDAO->getEvaluadoresByIdConjunto($idConjunto);
		
		$this->view->conjunto = $conjunto;
		$this->view->evaluacion = $encuesta;
		$this->view->evaluadores = $evaluadores;
    }

    public function asignacionesAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
        $conjunto = $this->conjuntoDAO->getRowConjuntoById($idConjunto);
        //$conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        $this->view->conjunto = $conjunto;
        $evaluaciones = $this->evaluacionDAO->getEvaluacionesByIdConjunto($idConjunto);
        
        $asignacionesGrupo = $this->evaluacionDAO->getAsignacionesByIdGrupo($conjunto["idGrupoEscolar"]);
        $evaluacionesConjunto = $this->evaluacionDAO->getAsignacionesByIdConjunto($idConjunto);
        
        $evaluacionesC = array();
        foreach ($evaluacionesConjunto as $idEncuesta => $asignaciones) {
            $arrAsignacionConjunto = array();
            $encuesta = $this->encuestaDAO->getEncuestaById($idEncuesta);
            foreach ($asignaciones as $key => $asignacion) {
                //print_r($asignacion);print_r("<br />");
                $arrAsignacionConjunto["idAsignacion"] = $asignacion["idAsignacionGrupo"];
                $arrAsignacionConjunto["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
                $arrAsignacionConjunto["docente"] = $this->docenteDAO->getDocenteById($asignacion['idDocente']);//registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
                $arrAsignacionConjunto["evaluacion"] = $encuesta;
                $evaluacionesC[] = $arrAsignacionConjunto;
            }
        }
        
        $evaluacionesG = array();
        foreach ($asignacionesGrupo as $asignacionGrupo) {
            $arrAsignacionGrupo = array();
            //print_r($asignacionGrupo); print_r("<br /><br />");
            
            $arrAsignacionGrupo["asignacion"] = $asignacionGrupo;
            $arrAsignacionGrupo["materia"] = $this->materiaDAO->getMateriaById($asignacionGrupo["idMateriaEscolar"]);
            $arrAsignacionGrupo["docente"] = $this->docenteDAO->getDocenteById($asignacionGrupo['idDocente']);
            $evaluacionesG[] = $arrAsignacionGrupo;
            
        }
        
        //print_r($evaluacionesC);
        $this->view->evaluaciones = $evaluaciones;
        $this->view->asignacionesConjunto = $evaluacionesC;
        $this->view->asignacionesGrupo = $evaluacionesG;
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

    public function editasignAction() {
        // action body
        $idConjunto = $this->getParam("co");
        $idAsignacion = $this->getParam("as");
        $idEvaluacion = $this->getParam("ev");
        $request = $this->getRequest();
        
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        $evaluacion = $this->encuestaDAO->obtenerEncuestaById($idEvaluacion);
        $evaluacionesConjunto = $this->evaluacionDAO->getEvaluacionesByIdConjunto($idConjunto);
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            print_r($datos);
            
            //$this->evaluacionDAO->asociarEvaluacionAConjunto($idConjunto, $datos["evaluacion"]);
        }
        
        $this->view->conjunto = $conjunto;
        $this->view->evaluacion = $evaluacion;
        $this->view->evaluacionesConjunto = $evaluacionesConjunto;
        
    }

    public function delasignAction()
    {
        // action body
    }


}
























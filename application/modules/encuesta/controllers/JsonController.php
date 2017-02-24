<?php

class Encuesta_JsonController extends Zend_Controller_Action
{
	private $encuestaDAO = null;
    private $gradosDAO = null;
    private $gruposDAO = null;
    private $cicloDAO = null;
    private $asignacionDAO = null;
    private $materiaDAO = null;
    private $registroDAO = null;
    private $reporteDAO = null;
    private $nivelDAO = null;
	private $encoder = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($dataIdentity["adapter"]);
        $this->gradosDAO = new Encuesta_DAO_Grado($dataIdentity["adapter"]);
		$this->gruposDAO = new Encuesta_DAO_Grupos($dataIdentity["adapter"]);
		$this->cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
		$this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($dataIdentity["adapter"]);
        $this->reporteDAO = new Encuesta_DAO_Reporte($dataIdentity["adapter"]);
        
		
		$this->materiaDAO = new Encuesta_DAO_Materia($dataIdentity["adapter"]);
		$this->registroDAO = new Encuesta_DAO_Registro($dataIdentity["adapter"]);
        $this->nivelDAO = new Encuesta_DAO_Nivel($dataIdentity["adapter"]);
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->encoder = new App_Util_Encode;
    }

    public function indexAction()
    {
        // action body
    }

    public function encrealizadasAction()
    {
        // action body
        $idGrupoEscolar = $this->getParam("idGrupo");
        $encuestaDAO = $this->encuestaDAO;
		$asignacionDAO = $this->asignacionDAO;
		//Materia y Docente
		$materiaDAO = $this->materiaDAO;
		$registroDAO = $this->registroDAO;
		
		$encuestasRealizadas = $encuestaDAO->getEncuestasRealizadasByIdGrupoEscolar($idGrupoEscolar);
		// array de encuestas realizadas extendido.
		$arrayERExt = array();
		foreach ($encuestasRealizadas as $er) {
			$asignacionGrupo = $asignacionDAO->getAsignacionById($er["idAsignacionGrupo"]);
			$materia = $materiaDAO->obtenerMateria($asignacionGrupo["idMateriaEscolar"]);
			$docente = $registroDAO->obtenerRegistro($asignacionGrupo["idRegistro"]);
			$encuesta = $encuestaDAO->getEncuestaById($er["idEncuesta"]);
			
			$contenedor = array();
			$contenedor["asignacion"] = $er;
			$contenedor["encuesta"] = $encuesta->toArray();
			$contenedor["docente"] = $docente->toArray();
			$contenedor["materia"] = $materia->toArray();
			
			$arrayERExt[] = $contenedor;
		}
		
		echo Zend_Json::encode($arrayERExt);
    }

    public function gradosAction()
    {
        // action body
        $idNivel = $this->getParam("idNivel");
		$grados = $this->gradosDAO->getGradosByIdNivel($idNivel);
		
		$arrayGrados = array();
		
		foreach ($grados as $grado) {
			$arrayGrados[] = array("idGradoEducativo"=>$grado->getIdGradoEducativo(), "gradoEducativo"=>$grado->getGradoEducativo());
		}
		
		echo Zend_Json::encode($arrayGrados);
    }

    public function gruposAction()
    {
        // action body
        $idCiclo = $this->getParam("idCiclo");
		$idGrado = $this->getParam("idGrado");
		if(is_null($idCiclo)) $idCiclo = $this->cicloDAO->getCurrentCiclo()->getIdCiclo();
		$grupos = $this->gruposDAO->obtenerGrupos($idGrado, $idCiclo);
		$arrayGrupos = array();
		
		foreach ($grupos as $grupo) {
			$arrayGrupos[] = array("idGrupo"=>$grupo->getIdGrupo(),"grupo"=>$grupo->getGrupo());
		}
		
		echo Zend_Json::encode($arrayGrupos);
    }

    public function reposdocenteAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idDocente = $this->getParam("idDocente");
        $idCicloEscolar = $this->getParam("idCicloEscolar");
		
		// Obtenemos los reportes del docente mediante
        $reportes = $this->reporteDAO->getReportesDocenteByIdCiclo($idCicloEscolar, $idDocente);
		$reposEncuestas = array();
        
        foreach ($reportes as $key => $value) {
        	$container = array();
			$container["reporte"] = $value;
			//$reporte = new Encuesta_Model_Registro($value);
			
            //Obtenemos la encuesta
			$encuesta = $this->encuestaDAO->obtenerEncuestaById($value["idEncuesta"]);
			$encModel = new Encuesta_Model_Encuesta($encuesta);
			//$encModel->toArray();
			$container["encuesta"] = $encModel->toArray();
			$asignacion = $this->asignacionDAO->getAsignacionById($value["idAsignacionGrupo"]);
			//print_r($asignacion);
			$materia = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
			$materiaModel = new Encuesta_Model_Materia($materia);
			$grupoEscolar = $this->gruposDAO->obtenerGrupo($asignacion["idGrupoEscolar"]);
			$grado = $this->gradosDAO->getGradoById($grupoEscolar->getIdGrado());
			$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
			$detalle = array();
			$detalle["materia"] = $materiaModel->toArray(); // materia viene como array
			$detalle["grupo"] = $grupoEscolar->toArray();
			$detalle["grado"] = $grado->toArray();
			$detalle["nivel"] = $nivel->toArray();
			
			$container["detalle"] = $detalle;
			$reposEncuestas[] = $container;
			//print_r("<br /><br />");
			//$container["detalle"] = 
			//echo json_encode($this->encoder->encodeArray($encuesta));
			
        }
		
		//$reposEncuestas = $this->encoder->utf8_encode_deep($reposEncuestas);
		
		echo Zend_Json::encode($reposEncuestas);
    }


}










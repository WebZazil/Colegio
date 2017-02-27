<?php

class Encuesta_GrupoeController extends Zend_Controller_Action
{

    private $gradoDAO = null;
    private $cicloDAO = null;
    private $gruposDAO = null;
    private $nivelDAO = null;
    private $materiaDAO = null;
    private $encuestaDAO = null;
    private $registroDAO = null;
    private $preferenciaDAO = null;
    private $planDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
		
        $this->gruposDAO = new Encuesta_DAO_Grupos($dataIdentity["adapter"]);
		$this->cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
		$this->gradoDAO = new Encuesta_DAO_Grado($dataIdentity["adapter"]);
		$this->nivelDAO = new Encuesta_DAO_Nivel($dataIdentity["adapter"]);
		$this->materiaDAO = new Encuesta_DAO_Materia($dataIdentity["adapter"]);
		$this->encuestaDAO = new Encuesta_DAO_Encuesta($dataIdentity["adapter"]);
		$this->registroDAO = new Encuesta_DAO_Registro($dataIdentity["adapter"]);
		$this->preferenciaDAO = new Encuesta_DAO_Preferencia($dataIdentity["adapter"]);
		$this->planDAO = new Encuesta_DAO_Plan($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $idGrupo = $this->getParam("idGrupo");
		$grupo = $this->gruposDAO->obtenerGrupo($idGrupo);
		//$ciclo = $this->cicloDAO->obtenerCiclo($grupo->getIdCiclo());
		$ciclo = $this->cicloDAO->getCicloById($grupo->getIdCiclo());
		//$grado = $this->gradoDAO->obtenerGrado($grupo->getIdGrado());
		$grado = $this->gradoDAO->getGradoById($grupo->getIdGrado());
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
		//$nivel = $this->nivelDAO->;
		$materias = $this->materiaDAO->obtenerMateriasGrupo($ciclo->getIdCiclo(), $grupo->getIdGrado());
		
		$this->view->nivel = $nivel;
		$this->view->grado = $grado;
		$this->view->grupo = $grupo;
		$this->view->materias = $materias;
		
		$profesores = $this->gruposDAO->obtenerDocentes($idGrupo);
		$this->view->profesores = $profesores;
    }

    public function adminAction()
    {
        // action body
        $request = $this->getRequest();
		$idGrupo = $this->getParam("idGrupo");
		$materiasRelacionadas = $this->gruposDAO->obtenerMaterias($idGrupo);
		
		$grupo = $this->gruposDAO->obtenerGrupo($idGrupo);
		$grado = $this->gradoDAO->getGradoById($grupo->getIdGrado());
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
		
		$idsRelacionadas = explode(",", $grupo->getIdsMaterias());
		
		$this->view->grupo = $grupo;
		$this->view->grado = $grado;
		$this->view->nivel = $nivel;
		//$this->view->materias = $materias;
		$materiasGrado = $this->materiaDAO->getMateriasByIdGradoAndCurrentCiclo($grado->getIdGradoEducativo());
		$materiasDisponibles = array();
		
		if (empty($idsRelacionadas)) {
			//print_r($idsRelacionadas);
			//print_r($idsRelacionadas);
			$materiasDisponibles = $materiasGrado;
		}else{
			foreach ($materiasGrado as $materiaGrado) {
				if (!in_array($materiaGrado["idMateriaEscolar"], $idsRelacionadas)) {
					$materiasDisponibles[] = $materiaGrado;
				}
			}
		}
		
		//print_r("<br />Desplegando materias<br />");
		//print_r($materiasDisponibles);
		$this->view->materiasDisponibles = $materiasDisponibles;
		$this->view->materiasAsociadas = $materiasRelacionadas;
		/*
		$idsMateriaGrado = array();
		
		foreach ($materiasGrado as $materiaGrado) {
			$idsMateriaGrado[] = $materiaGrado["idMateriaEscolar"];
		}
		
		$materiasDisponibles = array();
		foreach ($materiasGrado as $materiaGrado) {
			foreach ($materiasRelacionadas as $materiaRelacionada) {
				if($materiaGrado["idMateriaEscolar"] == $materiaRelacionada["idMateriaEscolar"]){
					$materiasDisponibles[] = $materiaGrado;
				}
			}
		}
		*/
    }

    public function consultaAction()
    {
        // action body
        $request = $this->getRequest();
		$formulario = new Encuesta_Form_ConsultaGrupos;
		
        $idGrado = $this->getParam("idGrado");
		$idNivel = $this->getParam("idNivel");
		$ciclo = $this->cicloDAO->getCurrentCiclo();
		$this->view->ciclo = $ciclo;
		
		//Cuando viene la la vista encuesta/grado/admin/idGrado/valor
		//No desplegamos formulario de consulta, traemos tabla con los grupos del grado del ciclo actual
		if(!is_null($idGrado)){
			$grado = $this->gradoDAO->getGradoById($idGrado);
			$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
			
			$grupos = $this->gruposDAO->obtenerGrupos($idGrado, $ciclo->getIdCiclo());
			
			$this->view->nivel = $nivel;
			$this->view->grado = $grado;
			
			$this->view->grupos = $grupos;
			return;
		}elseif(!is_null($idNivel)){
			$nivel = $this->nivelDAO->obtenerNivel($idNivel);
			$grados = $this->gradoDAO->getGradosByIdNivel($idNivel);
			
			if(!is_null($grados)){
				$formulario->getElement("grado")->clearMultiOptions();
				$formulario->getElement("idNivelEducativo")->clearMultiOptions();
				$formulario->getElement("idNivelEducativo")->addMultiOption($nivel->getIdNivel(),$nivel->getNivel());
				foreach ($grados as $grado) {
					$formulario->getElement("grado")->addMultiOption($grado->getIdGradoEducativo(),$grado->getGradoEducativo());
				}
			}
			
			$this->view->nivel = $nivel;
			$this->view->grupos = array();
		}
		
		$this->view->formulario = $formulario;
		//$this->view->ciclo = $this->cicloDAO->obtenerCicloActual();
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				
				$grupos = $this->gruposDAO->obtenerGrupos($datos["grado"], $datos["ciclo"]);
				$this->view->grado = $this->gradoDAO->obtenerGrado($datos["grado"]);
				$this->view->grupos = $grupos;
			}
		}
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
		$idGrado = $this->getParam("idGrado");
		//$grado = $this->gradoDAO->obtenerGrado($idGrado);
		$grado = $this->gradoDAO->getGradoById($idGrado);
		
		$formulario = new Encuesta_Form_AltaGrupoEscolar;
		$formulario->getElement("idGradoEducativo")->addMultiOption($grado->getIdGradoEducativo(),$grado->getGradoEducativo());
        
		$this->view->formulario = $formulario;
		$this->view->grado = $grado;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				try{
					$this->gruposDAO->crearGrupo($datos);
					$this->view->messageSuccess = "Grupo: <strong>".$datos["grupoEscolar"]."</strong> dado de alta en el Grado: <strong>" . $grado->getGradoEducativo() . "</strong> exitosamente";
				}catch(Util_Exception_BussinessException $ex){
					$this->view->messageFail = $ex->getMessage();
				}
				
			}
		}
    }

    public function opcionesAction()
    {
        // action body
        $idGrupo = $this->getParam("idGrupo");
		$grupo = $this->gruposDAO->obtenerGrupo($idGrupo);
		$grado = $this->gradoDAO->obtenerGrado($grupo->getIdGrado());
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivel());
		$ciclo = $this->cicloDAO->obtenerCiclo($grupo->getIdCiclo());
        
        $this->view->grupo = $grupo;
        $this->view->grado = $grado;
		$this->view->nivel = $nivel;
        $this->view->ciclo = $ciclo;
    }

    public function asociarpAction()
    {
        // action body
        $request = $this->getRequest();
        $idGrupo = $this->getParam("idGrupo");
		$idMateria = $this->getParam("idMateria");
		
		$grupo = $this->gruposDAO->obtenerGrupo($idGrupo);
		$materia = $this->materiaDAO->obtenerMateria($idMateria);
		
		$formulario = new Encuesta_Form_MateriasProfesor;
		$formulario->getElement("idMateria")->clearMultiOptions();
		$formulario->getElement("idMateria")->addMultiOption($materia->getIdMateria(),$materia->getMateria());
		
		$this->view->grupo = $grupo;
		$this->view->formulario = $formulario;
		if($request->getPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				//print_r($datos);
				$registro = array();
				$registro["idGrupo"] = $idGrupo;
				$registro["idRegistro"] = $datos["idProfesor"];
				$registro["idMateria"] = $datos["idMateria"];
				try{
					$this->gruposDAO->agregarDocenteGrupo($registro);
					$docente = $this->registroDAO->obtenerRegistro($registro["idRegistro"]);
					$this->view->messageSuccess = "Docente: <strong>".$docente->getApellidos().", ".$docente->getNombres()."</strong> asociado a la materia <strong>".$materia->getMateria()."</strong> exitosamente.";
				}catch(Util_Exception_BussinessException $ex){
					$this->view->messageFail = $ex->getMessage();
				}
			}
		}
    }

    public function aencuestaAction()
    {
        // action body
        $request = $this->getRequest();
        $idGrupo = $this->getParam("idGrupo");
		//$idAsignacion = $this->getParam("idAsignacion");
		
		$grupo = $this->gruposDAO->obtenerGrupo($idGrupo);
		$grado = $this->gradoDAO->obtenerGrado($grupo->getIdGrado());
		$ciclo = $this->cicloDAO->obtenerCiclo($grupo->getIdCiclo());
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivel());
		
		//$docente = $this->registroDAO->obtenerRegistro($idDocente);
		// En la vista no se puede hacer esto por que aun no hay asociacion, llegamos aqui ya despues de hacer una asociacion, no antes
		//$formulario = new Encuesta_Form_AsociarEncuestaDocente; 
		$formulario = new Encuesta_Form_AsociarEncuesta;
		
		$docentes = $this->gruposDAO->obtenerDocentes($idGrupo);
		foreach ($docentes as $docente) {
			$label = $docente["profesor"]->getApellidos().", ".$docente["profesor"]->getNombres()." -- ".$docente["materia"]->getMateria();
			$formulario->getElement("idAsignacion")->addMultiOption($docente["idAsignacion"], $label);
		}
		
		$this->view->grupo = $grupo;
		$this->view->grado = $grado;
		$this->view->ciclo = $ciclo;
		$this->view->nivel = $nivel;
		$this->view->formulario = $formulario;
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				//$datos["idGrupo"] = $idGrupo;
				//print_r($datos);
				$encuesta = $this->encuestaDAO->obtenerEncuesta($datos["idEncuesta"]);
				$asignacion = $this->gruposDAO->obtenerAsignacion($datos["idAsignacion"]);
				
				$materia = $this->materiaDAO->obtenerMateria($asignacion["idMateria"]);
				$docente = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"]);
				
				try{
					$this->encuestaDAO->agregarEncuestaGrupo($datos);
					$mensaje = "Encuesta <strong>".$encuesta->getNombre()."</strong> asociada correctamente con Docente-Materia: <strong>".$docente->getApellidos().", ".$docente->getNombres()." - " . $materia->getMateria()."</strong>";
					$this->view->messageSuccess = $mensaje;
				}catch(Util_Exception_BussinessException $ex){
					$this->view->messageFail = $ex->getMessage() ." en el grupo: <strong>".$grupo->getGrupo()."</strong>";
					//print_r($ex->getMessage());
				}
				
			}
		}
    }

    public function encuestasAction()
    {
        // action body
        $idAsignacion = $this->getParam("idAsignacion");
		$asignacion = $this->gruposDAO->obtenerAsignacion($idAsignacion);
		
        $idGrupo = $asignacion["idGrupo"];
		$idDocente = $asignacion["idRegistro"];
		$idMateria = $asignacion["idMateria"];
		
		$grupo = $this->gruposDAO->obtenerGrupo($idGrupo);
		$materia = $this->materiaDAO->obtenerMateria($idMateria);
		$docente = $this->registroDAO->obtenerRegistro($idDocente);
		
        $encuestas = $this->encuestaDAO->obtenerEncuestasRealizadasPorAsignacion($idAsignacion);
        
		$this->view->grupo = $grupo;
		$this->view->materia = $materia;
		$this->view->docente = $docente;
        $this->view->encuestas = $encuestas;
		$this->view->asignacion = $asignacion;
    }

    public function resultadoAction()
    {
        // action body
        $idEncuesta = $this->getParam("idEncuesta");
		$idDocente = $this->getParam("idDocente");
		$idGrupo = $this->getParam("idGrupo");
		$preferenciaDAO = $this->preferenciaDAO;
    }

    public function asociarmAction()
    {
        // action body
        $request = $this->getRequest();
		if($request->isPost()){
			$idGrupoEscolar = $this->getParam("idGrupoEscolar");
			$datos = $request->getPost();
			print_r($datos);
			
			$this->gruposDAO->asociarMateriaAgrupo($idGrupoEscolar, $datos["idMateriaEscolar"]);
			$this->_helper->redirector->gotoSimple("admin", "grupoe", "encuesta", array("idGrupo"=>$idGrupoEscolar));
		}else{
			$this->_helper->redirector->gotoSimple("index", "nivel", "encuesta");
		}
    }


}




















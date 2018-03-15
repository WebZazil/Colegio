<?php

class Encuesta_GrupoeController extends Zend_Controller_Action
{

    private $grupoeDAO = null;
    private $gradoDAO = null;
    private $cicloDAO = null;
    private $nivelDAO = null;
    private $materiaDAO = null;
    private $planDAO = null;
    
    private $encuestaDAO = null;
    private $registroDAO = null;
    private $preferenciaDAO = null;
    private $asignacionDAO = null;
    
    private $docenteDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        
        $identity = $auth->getIdentity();
        
        $this->grupoeDAO = new Encuesta_Data_DAO_GrupoEscolar($identity['adapter']);
        $this->gradoDAO = new Encuesta_Data_DAO_GradoEducativo($identity['adapter']);
        $this->cicloDAO = new Encuesta_Data_DAO_CicloEscolar($identity['adapter']);
        $this->materiaDAO = new Encuesta_Data_DAO_Materia($identity['adapter']);
        $this->planDAO = new Encuesta_Data_DAO_PlanEducativo($identity['adapter']);
        $this->nivelDAO = new Encuesta_Data_DAO_NivelEducativo($identity['adapter']);
        $this->registroDAO = new Encuesta_Data_DAO_Registro($identity['adapter']);
        $this->asignacionDAO = new Encuesta_Data_DAO_AsignacionGrupo($identity['adapter']);
        $this->docenteDAO = new Encuesta_Data_DAO_Docente($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $idGrupo = $this->getParam("gpo");
		$grupo = $this->grupoeDAO->getGrupoById($idGrupo);
		//$ciclo = $this->cicloDAO->obtenerCiclo($grupo->getIdCiclo());
		$ciclo = $this->cicloDAO->getCicloEscolarById($grupo['idCicloEscolar']);
		//$grado = $this->gradoDAO->obtenerGrado($grupo->getIdGrado());
		$grado = $this->gradoDAO->getGradoEducativoById($grupo['idGradoEducativo']);
		$nivel = $this->nivelDAO->getNivelEducativoById($grado['idNivelEducativo']);
		//$nivel = $this->nivelDAO->;
		$materias = $this->materiaDAO->getMateriasByIdGrupoEscolar($idGrupo);
		
		$this->view->nivel = $nivel;
		$this->view->grado = $grado;
		$this->view->grupo = $grupo;
		$this->view->materias = $materias;
		
		//$profesores = $this->gruposDAO->obtenerDocentes($idGrupo);
		$this->view->profesores = array();
    }

    public function adminAction()
    {
        // action body
        $request = $this->getRequest();
		$idGrupo = $this->getParam('gpo');
		
		$grupo = $this->grupoeDAO->getGrupoById($idGrupo);
		$grado = $this->gradoDAO->getGradoEducativoById($grupo['idGradoEducativo']);
		$nivel = $this->nivelDAO->getNivelEducativoById($grado['idNivelEducativo']);
		$cicloEscolar = $this->cicloDAO->getCicloEscolarActual();
		
		$materiasGrado = $this->materiaDAO->getMateriasByIdGradoEducativoAndIdCicloEscolar($grado['idGradoEducativo'],$cicloEscolar['idCicloEscolar']);
		$materiasGrupo = $this->grupoeDAO->getMateriasGrupo($idGrupo);
		$this->view->materiasGrupo = $materiasGrupo;
		
		$idsRelacionadas = explode(",", $grupo['idsMaterias']);
		
		//$materiasGrado = $this->materiaDAO->getMateriasByIdGradoEducativo($grado['idGradoEducativo']);
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
		
		$this->view->grupo = $grupo;
		$this->view->grado = $grado;
		$this->view->nivel = $nivel;
		
		//$asignacionesGrupo = $this->asignacionDAO->getObjByIdAsignacion($idAsignacion);
		
		//print_r("<br />Desplegando materias<br />");
		//print_r($materiasDisponibles);
		$this->view->materiasDisponibles = $materiasDisponibles;
		//$this->view->materiasAsociadas = $materiasRelacionadas;
		$this->view->materiasGrado = $materiasGrado;
        //$this->asignacionDAO->getAsignacionById($id);
        $this->view->asignacionDAO = $this->asignacionDAO;
        $this->view->docenteDAO = $this->docenteDAO;
        
    }

    public function consultaAction()
    {
        // action body
        $request = $this->getRequest();
		$formulario = new Encuesta_Form_ConsultaGrupos;
		
		$idGrado = $this->getParam("gr");
		$idNivel = $this->getParam("nv");
		
		$ciclo = $this->cicloDAO->getCicloEscolarActual();
		$this->view->ciclo = $ciclo;
		
		//Cuando viene de la vista encuesta/grado/admin/gr/valor - encuesta/grupoe/admin/gpo/valor
		//No desplegamos formulario de consulta, traemos tabla con los grupos del grado del ciclo actual
		if(!is_null($idGrado)){
			$grado = $this->gradoDAO->getGradoEducativoById($idGrado);
			$nivel = $this->nivelDAO->getNivelEducativoById($grado['idNivelEducativo']);
			
			$grupos = $this->grupoeDAO->getGruposByIdGradoEducativoAndIdCicloEscolar($idGrado, $ciclo['idCicloEscolar']);
			
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
		$idGrado = $this->getParam("gr");
		
		$grado = $this->gradoDAO->getGradoEducativoById($idGrado);
		$nivel = $this->nivelDAO->getNivelEducativoById($grado['idNivelEducativo']);
		$ciclo = $this->cicloDAO->getCicloEscolarActual();
		
		$this->view->grado = $grado;
		$this->view->nivel = $nivel;
		$this->view->ciclo = $ciclo;
		
		if($request->isPost()){
		    $datos = $request->getPost();
		    $datos['idsMaterias'] = '';
		    $datos['creacion'] = date('Y-m-d H:i:s');
		    //print_r($datos);
		    try{
		        $this->grupoeDAO->addGrupoEscolar($datos);
		        $this->view->messageSuccess = "Grupo: <strong>".$datos["grupoEscolar"]."</strong> dado de alta en el Grado: <strong>" . $grado['gradoEducativo'] . "</strong> exitosamente";
		    }catch(Exception $ex){
		        $this->view->messageFail = $ex->getMessage();
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

    public function asocdocAction()
    {
        // action body
        $request = $this->getRequest();
        $idGrupo = $this->getParam("gpo");
		$idMateria = $this->getParam("mat");
		
		$grupo = $this->grupoeDAO->getGrupoById($idGrupo);
		$materia = $this->materiaDAO->getMateriaById($idMateria);
		
		$this->view->grupo = $grupo;
        $this->view->materia = $materia;
		$this->view->grupo = $grupo;
		
		if($request->getPost()){
		    $datos = $request->getPost();
		    
		    print_r($datos);
		    
		    $registro = array();
		    $registro["idGrupoEscolar"] = $idGrupo;
		    $registro["idRegistro"] = $datos["idProfesor"];
		    $registro["idMateriaEscolar"] = $datos["idMateriaEscolar"];
		    try{
		        //$this->gruposDAO->agregarDocenteGrupo($registro);
		        //$docente = $this->registroDAO->getRegistroById($registro["idRegistro"]);
		        //$this->view->messageSuccess = "Docente: <strong>".$docente->getApellidos().", ".$docente->getNombres()."</strong> asociado a la materia <strong>".$materia->getMateriaEscolar()."</strong> exitosamente.";
		        $this->_helper->redirector->gotoSimple("index", "grupoe", "encuesta", array("idGrupo"=>$idGrupo));
		    }catch(Exception $ex){
		        $this->view->messageFail = $ex->getMessage();
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
		//$grado = $this->gradoDAO->obtenerGrado($grupo->getIdGrado());
        $grado = $this->gradoDAO->getGradoById($grupo->getIdGrado());
		//$ciclo = $this->cicloDAO->obtenerCiclo($grupo->getIdCiclo());
        $ciclo = $this->cicloDAO->getCicloById($grupo->getIdCiclo());
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
		
		//$docente = $this->registroDAO->obtenerRegistro($idDocente);
		// En la vista no se puede hacer esto por que aun no hay asociacion, llegamos aqui ya despues de hacer una asociacion, no antes
		//$formulario = new Encuesta_Form_AsociarEncuestaDocente; 
		$formulario = new Encuesta_Form_AsociarEncuesta;
		
		$docentes = $this->gruposDAO->obtenerDocentes($idGrupo);
		foreach ($docentes as $docente) {
			$label = $docente["profesor"]->getApellidos().", ".$docente["profesor"]->getNombres()." -- ".$docente["materia"]->getMateriaEscolar();
			$formulario->getElement("idAsignacionGrupo")->addMultiOption($docente["idAsignacionGrupo"], $label);
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
				//$encuesta = $this->encuestaDAO->obtenerEncuesta($datos["idEncuesta"]);
                $encuesta = $this->encuestaDAO->getEncuestaById($datos["idEncuesta"]);
				$asignacion = $this->gruposDAO->obtenerAsignacion($datos["idAsignacionGrupo"]);
				
				$materia = $this->materiaDAO->obtenerMateria($asignacion["idMateriaEscolar"]);
				$docente = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"]);
				
				try{
					$this->encuestaDAO->agregarEncuestaGrupo($datos);
					$mensaje = "Encuesta <strong>".$encuesta->getNombre()."</strong> asociada correctamente con Docente-Materia: <strong>".$docente->getApellidos().", ".$docente->getNombres()." - " . $materia->getMateriaEscolar()."</strong>";
					$this->view->messageSuccess = $mensaje;
				}catch(Exception $ex){
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
		
        $idGrupo = $asignacion["idGrupoEscolar"];
		$idDocente = $asignacion["idRegistro"];
		$idMateria = $asignacion["idMateriaEscolar"];
		
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
			$idGrupoEscolar = $this->getParam("gpo");
			$datos = $request->getPost();
			//print_r($datos);
			
			$this->grupoeDAO->asociarMateriaAgrupo($idGrupoEscolar, $datos['idMateriaEscolar']);
			
			$this->_helper->redirector->gotoSimple("admin", "grupoe", "encuesta", array("gpo"=>$idGrupoEscolar));
		}else{
			$this->_helper->redirector->gotoSimple("index", "nivel", "encuesta");
		}
    }

    public function asociarmdAction()
    {
        // action body
        $idGrupo = $this->getParam("gpo");
        $idMateria = $this->getParam("mat");
        $idDocente = $this->getParam("doc");
        
        $datos = array();
        $datos["idGrupoEscolar"] = $idGrupo;
        $datos["idMateriaEscolar"] = $idMateria;
        $datos["idDocente"] = $idDocente;
        $datos['creacion'] = date('Y-m-d H:i:s');
        
        $this->asignacionDAO->addAsignacionGrupo($datos);
        
        $this->_helper->redirector->gotoSimple("admin", "grupoe", "encuesta", array("gpo"=>$idGrupo));
    }


}



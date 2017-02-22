<?php
/**
 * 
 */
class Encuesta_Util_Generator {
	//DAOS para guardar los resultados de encuesta
	private $encuestaDAO;
	private $seccionDAO;
	private $grupoDAO;
	private $preguntaDAO;
	private $categoriaDAO;
	private $opcionDAO;
	private $registroDAO;
	private $respuestaDAO;
	private $preferenciaDAO;
	
	private $materiaDAO;
	private $gruposDAO;
	private $gradoDAO;
	private $nivelDAO;
	
	private $formDecorators;
	private $decoratorsSeccion;
	private $decoratorsGrupo;
	private $decoratorsPregunta;
	
	public function __construct($dbAdapter) {
		$this->encuestaDAO = new Encuesta_DAO_Encuesta($dbAdapter);
		$this->seccionDAO = new Encuesta_DAO_Seccion($dbAdapter);
		$this->grupoDAO = new Encuesta_DAO_Grupo($dbAdapter);
		$this->preguntaDAO = new Encuesta_DAO_Pregunta($dbAdapter);
		$this->categoriaDAO = new Encuesta_DAO_Categoria($dbAdapter);
		$this->opcionDAO = new Encuesta_DAO_Opcion($dbAdapter);
		$this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
		$this->respuestaDAO = new Encuesta_DAO_Respuesta($dbAdapter);
		$this->preferenciaDAO = new Encuesta_DAO_Preferencia($dbAdapter);
		
		$this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
		$this->gruposDAO = new Encuesta_DAO_Grupos($dbAdapter);
		$this->gradoDAO = new Encuesta_DAO_Grado($dbAdapter);
		$this->nivelDAO = new Encuesta_DAO_Nivel($dbAdapter);
		
		$this->formDecorators = array(
			'FormElements',
			//'Fieldset',
			'Form'
		);
		
		$this->decoratorsSeccion = array(
			'FormElements',
			array(array('body' => 'HtmlTag'), array('tag' => 'tbody')),
			array(array('tabla' => 'HtmlTag'), array('tag' => 'table', 'class' => 'table table-striped table-condensed')),
			array('Fieldset', array('placement' => 'prepend')),
		);
		
		$this->decoratorsGrupo = array(
			//'Fieldset',
			'FormElements',
			array(array('body' => 'HtmlTag'), array('tag' => 'tbody')),
			array(array('tabla' => 'HtmlTag'), array('tag' => 'table', 'class' => 'table table-striped table-condensed')),
			array('Fieldset', array('placement' => 'prepend')),
			array(array('element' => 'HtmlTag'), array('tag' => 'td', 'colspan' => '2')),
			array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
		);
		
		$this->decoratorsPregunta = array(
			'ViewHelper', //array('ViewHelper', array('class' => 'form-control') ), //'ViewHelper', 
			array(array('element' => 'HtmlTag'), array('tag' => 'td')), 
			array('Label', array('tag' => 'td') ), 
			//array('Description', array('tag' => 'td', 'class' => 'label')), 
			array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
		);
	}
	
	public function generarFormulario($idEncuesta, $idAsignacion)
	{
		
		$encuesta = $this->encuestaDAO->obtenerEncuesta($idEncuesta);
		$asignacion = $this->gruposDAO->obtenerAsignacion($idAsignacion);
		$ticket = $this->encuestaDAO->obtenerNumeroConjuntoAsignacion($idEncuesta, $idAsignacion);
		
		$secciones = $this->seccionDAO->obtenerSecciones($idEncuesta);
		
		$grupoe = $this->gruposDAO->obtenerGrupo($asignacion["idGrupo"]);
		$grado = $this->gradoDAO->obtenerGrado($grupoe->getIdGrado());
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivel());
		$docente = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"]);
		$materia = $this->materiaDAO->obtenerMateria($asignacion["idMateria"]);
		
		$formulario = new Zend_Form($encuesta->getHash());
		
		$eSubCabecera = new Zend_Form_SubForm;
		$eSubCabecera->setLegend("Evaluación de Habilidades del Docente");
		
		$eEncuesta = new Zend_Form_Element_Select("idEncuesta");
		$eEncuesta->setLabel("Encuesta: ");
		$eEncuesta->setDecorators($this->decoratorsPregunta);
		$eEncuesta->setAttrib("disabled", "disabled");
		$eEncuesta->setAttrib("class", "form-control");
		$eEncuesta->addMultiOption($encuesta->getIdEncuesta(),$encuesta->getNombre());
		
		//$eLogo = new Zend_Form_Element_Image("logo");
		//$eLogo->setImage($this->view->baseUrl() . "/images/Logo.png");
		//$eLogo->setDecorators($this->decoratorsPregunta);
		//$formulario->addElement($eLogo);
		
		$eNivel = new Zend_Form_Element_Select("idNivel");
		$eNivel->setLabel("Nivel: ");
		$eNivel->setAttrib("class", "form-control");
		$eNivel->setAttrib("disabled", "disabled");
		$eNivel->addMultiOption($nivel->getIdNivel(),$nivel->getNivel());
		$eNivel->setDecorators($this->decoratorsPregunta);
		
		$eGrado = new Zend_Form_Element_Select("idGrado");
		$eGrado->setLabel("Grado: ");
		$eGrado->setAttrib("class", "form-control");
		$eGrado->setAttrib("disabled", "disabled");
		$eGrado->addMultiOption($grado->getIdGrado(),$grado->getGrado());
		$eGrado->setDecorators($this->decoratorsPregunta);
		
		$eGrupo = new Zend_Form_Element_Select("idGrupo");
		$eGrupo->setLabel("Grupo: ");
		$eGrupo->setAttrib("class", "form-control");
		$eGrupo->setAttrib("disabled", "disabled");
		$eGrupo->addMultiOption($grupoe->getIdGrupo(),$grupoe->getGrupo());
		$eGrupo->setDecorators($this->decoratorsPregunta);
		
		$eReferencia = new Zend_Form_Element_Select("idRegistro");
		$eReferencia->setLabel("Docente : ");
		$eReferencia->addMultiOption($docente->getIdRegistro(),$docente->getApellidos().", ".$docente->getNombres());
		//$eReferencia->setValue($docente->getApellidos().", ".$docente->getNombres());
		$eReferencia->setAttrib("class", "form-control");
		$eReferencia->setAttrib("disabled", "disabled");
		$eReferencia->setDecorators($this->decoratorsPregunta);
		
		$eMateria = new Zend_Form_Element_Select("materia");
		$eMateria->setLabel("Materia: ");
		$eMateria->setAttrib("class", "form-control");
		$eMateria->setAttrib("disabled", "disabled");
		$eMateria->addMultiOption($materia->getIdMateria(),$materia->getMateria());
		//$eMateria->setValue($materia->getMateria());
		$eMateria->setDecorators($this->decoratorsPregunta);
		
		$eSubCabecera->addElements(array($eEncuesta,$eNivel,$eGrado,$eGrupo,$eMateria,$eReferencia));
		$eSubCabecera->setDecorators($this->decoratorsSeccion);
		
		$formulario->addSubForm($eSubCabecera, "referencia");
		
		//============================================= Iteramos a traves de las secciones del grupo
		foreach ($secciones as $seccion) {
			//============================================= Cada seccion es una subforma
			$subFormSeccion = new Zend_Form_SubForm($seccion->getHash());
			//$subFormSeccion->setLegend("Sección: " .$seccion->getNombre());
			//============================================= Obtenemos los elementos de la seccion
			$grupos = $this->seccionDAO->obtenerGrupos($seccion->getIdSeccion());
			$preguntas = $this->seccionDAO->obtenerPreguntas($seccion->getIdSeccion());
			
			$elementos = array();
			
			foreach ($grupos as $grupo) {
				$elementos[$grupo->getOrden()] = $grupo;
			}
			
			foreach ($preguntas as $pregunta) {
				$elementos[$pregunta->getOrden()] = $pregunta;
			}
			
			ksort($elementos);
			//print_r($elementos);
			
			foreach ($elementos as $elemento) {
				//============================================= Verificamos que tipo de elemento es
				if($elemento instanceof Encuesta_Model_Pregunta){
					//============================================= Aqui ya la agregamos a la seccion
					$this->agregarPregunta($subFormSeccion, $elemento);
				}elseif($elemento instanceof Encuesta_Model_Grupo){
					//============================================= un grupo es otra subform
					$subFormGrupo = new Zend_Form_SubForm($elemento->getHash());
					//$subFormGrupo->setLegend("Grupo: " . $elemento->getNombre());
					$preguntasGrupo = $this->grupoDAO->obtenerPreguntas($elemento->getIdGrupo());
					foreach ($preguntasGrupo as $pregunta) {
						//============================================= Aqui ya la agregamos al grupo
						$this->agregarPregunta($subFormGrupo, $pregunta);
					}
					$subFormGrupo->setDecorators($this->decoratorsGrupo);
					$subFormSeccion->addSubForm($subFormGrupo, $elemento->getIdGrupo());
				}
			}
			$subFormSeccion->setDecorators($this->decoratorsSeccion);
			$formulario->addSubForm($subFormSeccion, $seccion->getIdSeccion());
			
		}
		
		$eSubmit = new Zend_Form_Element_Submit("submit");
		$eSubmit->setLabel("Enviar Encuesta");
		$eSubmit->setAttrib("class", "btn btn-success");
		//$eSubmit->setAttrib("disabled", "disabled");
		
		//$eConjunto = new Zend_Form_Element_Hidden("conjunto");
		//$eConjunto->setValue($ticket);
		
		$formulario->addElement($eSubmit);
		//$formulario->addElement($eConjunto);
		$formulario->setDecorators($this->formDecorators);
		
		return $formulario;
	}

	public function procesarFormulario($idEncuesta,$idAsignacion,$post)
	{
		//print_r($post);
		//print_r("<br />");
		//print_r("============================");
		//print_r("<br />");
		unset($post["submit"]);
		$contenedores = array_values($post);
		//print_r($contenedores);
		//print_r("<br />");
		//print_r("============================");
		//print_r("<br />");
		// $sop: seccion o pregunta
		$arrRespuestas = array();
		//$arrRespuestas[0] = date("Y-m-d H:i:s", time());
		foreach ($contenedores as $seccion) {
			//print_r("En contenedor");
			//print_r("<br />");
			//print_r($seccion);
			//print_r("<br />");
			//print_r("============================");
			//print_r("<br />");
			//$gpo: grupo o pregunta
			foreach ($seccion as $gop => $value) {
				//print_r($gop);
				//print_r("<br />");
				//print_r("============================");
				//print_r("<br />");
				//Si value es array entonces es un grupo, $gop es la clave del grupo y $value son las respuestas del grupo
				if(is_array($value)){
					//$arrayRespuestas[] = 
					//print_r($value);
					//print_r("<br />");
					//print_r("============================");
					//print_r("<br />");
					foreach ($value as $idPregunta => $respuesta) {
						//$arrRespuestas[] = array($idPregunta => $respuesta);
						//print_r("IdPregunta: ".$idPregunta. " - Respuesta: ".$respuesta);
						//print_r("<br />");
						$arrRespuestas[$idPregunta] = $respuesta;
					}
				}else{
					//print_r("IdPregunta: ".$gop." - Respuesta: ".$value);
					//print_r("<br />");
					//print_r("============================");
					//print_r("<br />");
					$arrRespuestas[$gop] = $value;
				}
			}
			
			//print_r($arrRespuestas);
			//ksort($arrRespuestas);
		}
		
		//$hashEncuesta = Util_Secure::generateKey($arrRespuestas);
		//print_r("Hash de la encuesta: ".$hashEncuesta);
		//print_r("<br />");
		$encuestaDAO = $this->encuestaDAO;
		$preguntaDAO = $this->preguntaDAO;
		$respuestaDAO = $this->respuestaDAO;
		$preferenciaDAO = $this->preferenciaDAO;
		$conjunto = $encuestaDAO->obtenerNumeroConjuntoAsignacion($idEncuesta, $idAsignacion);
		$conjunto++;
		//print_r("<br />");
		//print_r("Conjunto: ".$conjunto);
		//print_r("<br />");
		foreach ($arrRespuestas as $idPregunta => $respuesta) {
			
			$pregunta = $preguntaDAO->obtenerPregunta($idPregunta);
			
			$datos = array();
			$datos["idEncuesta"] = $idEncuesta;
			$datos["idAsignacion"] = $idAsignacion;
			$datos["idPregunta"] = $idPregunta;
			$datos["respuesta"] = $respuesta;
			$datos["conjunto"] = $conjunto;
			$modelRespuesta = new Encuesta_Model_Respuesta($datos);
			
			$mResp = $respuestaDAO->crearRespuesta($idEncuesta, $modelRespuesta);
			
			if($pregunta->getTipo() == "SS"){
				$this->preferenciaDAO->agregarPreferenciaPreguntaAsignacion($idAsignacion, $idPregunta, $respuesta); //($idPregunta,
			}
			
		}
		$registro = array();
		$registro["idEncuesta"] = $idEncuesta;
		$registro["idAsignacion"] = $idAsignacion;
		$encuestaDAO->agregarEncuestaRealizada($registro);
		
	}
	
	private function agregarPregunta(Zend_Form $contenedor, Encuesta_Model_Pregunta $pregunta)
	{
		$ePregunta = null;
		if($pregunta->getTipo() == "AB"){
			$ePregunta = new Zend_Form_Element_Textarea($pregunta->getIdPregunta());
			$ePregunta->setAttrib("class", "form-control");
			$ePregunta->setAttrib("rows", "2");
		}else{
			//Obtenemos las Opciones
			$opciones = $this->opcionDAO->obtenerOpcionesPregunta($pregunta->getIdPregunta());
			if($pregunta->getTipo() == "SS"){
				$ePregunta = new Zend_Form_Element_Radio($pregunta->getIdPregunta());
				$ePregunta->setAttrib("required", "required");
			}elseif($pregunta->getTipo() == "MS"){
				$ePregunta = new Zend_Form_Element_MultiCheckbox($pregunta->getIdPregunta());
			}
			
			foreach ($opciones as $opcion) {
				$ePregunta->addMultiOption($opcion->getIdOpcion(), $opcion->getOpcion());//->setSeparator("");
			}
		}
		$ePregunta->setLabel($pregunta->getPregunta());
		//$ePregunta->setAttrib("class", "form-control");
		$ePregunta->setDecorators($this->decoratorsPregunta);
		$contenedor->addElement($ePregunta);
		
		return $contenedor;
	}
	
	public function crearRespuesta($value='')
	{
		$d;
	}
}

?>
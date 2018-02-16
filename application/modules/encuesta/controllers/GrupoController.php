<?php

class Encuesta_GrupoController extends Zend_Controller_Action
{
	private $grupoDAO;
    private $seccionDAO;
    private $preguntaDAO;
	private $opcionDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
        $this->grupoDAO = new Encuesta_DAO_Grupo($identity["adapter"]);
		$this->seccionDAO = new Encuesta_DAO_Seccion($identity["adapter"]);
		$this->preguntaDAO = new Encuesta_DAO_Pregunta($identity["adapter"]);
		$this->opcionDAO = new Encuesta_DAO_Opcion($identity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $idGrupo = $this->getParam("idGrupo");
		
		if(!is_null($idGrupo)){
			
			$this->view->grupo = $this->grupoDAO->getGrupoById($idGrupo);//->obtenerGrupo($idGrupo);
			//$this->view->preguntas = $this->preguntaDAO->obtenerPreguntas($idGrupo, "grupo");
			$this->view->preguntas = $this->grupoDAO->getPreguntasByIdGrupo($idGrupo);//->obtenerPreguntas($idGrupo);
		}else{
			//Redirect
		}
    }

    public function adminAction()
    {
        // action body
        $idGrupo = $this->getParam("idGrupo");
		
		
		if(!is_null($idGrupo)){
			$grupo = $this->grupoDAO->getGrupoById($idGrupo);//->obtenerGrupo($idGrupo);
			$seccion = $this->seccionDAO->getSeccionById($grupo->getIdSeccionEncuesta());//->obtenerSeccion($grupo->getIdSeccion());
			//$preguntas = $this->preguntaDAO->obtenerPreguntas($idGrupo, "G");
			$preguntas = $this->grupoDAO->getPreguntasByIdGrupo($grupo->getIdGrupoSeccion());//->obtenerPreguntas($grupo->getIdGrupoSeccion());
			$t = Zend_Registry::get("tipo");
            /*
			$formulario = new Encuesta_Form_AltaGrupo;
			$formulario->getElement("nombre")->setValue($grupo->getNombre());
			//$formulario->getElement("tipo")->setMultiOptions($t);
			$formulario->getElement("submit")->setLabel("Actualizar Grupo");
			$formulario->getElement("submit")->setAttrib("class", "btn btn-warning");
			*/
			$this->view->grupo = $grupo;
			$this->view->seccion = $seccion;
			$this->view->preguntas = $preguntas;
			//$this->view->formulario = $formulario;
			
			if($grupo->getTipo() != "AB"){
				$opcionesGrupo = $this->opcionDAO->obtenerOpcionesGrupo($idGrupo);
				$this->view->opciones = $opcionesGrupo;
			}
		}else{
			$this->_helper->redirector->gotoSimple("index", "index", "encuesta");
		}
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $formulario = new Encuesta_Form_AltaGrupo;
		
		$idSeccion = $this->getParam("id");
		$seccion = $this->seccionDAO->getSeccionById($idSeccion);
		
		$this->view->formulario = $formulario;
		$this->view->seccion = $seccion;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())) {
				$datos = $formulario->getValues();
				
				$grupo = new Encuesta_Models_Grupo($datos);
				
				$grupo->setIdSeccionEncuesta($idSeccion);
				$grupo->setElementos("0");
				$grupo->setOrden($seccion->getElementos() + 1);
				
				$idGrupo = $this->grupoDAO->addGrupoToSeccion($grupo);
				if($grupo->getTipo() == "AB"){
					$this->_helper->redirector->gotoSimple("index", "index", "encuesta");
				}else{
					$this->_helper->redirector->gotoSimple("opciones", "grupo", "encuesta", array("idGrupo"=>$idGrupo));
				}
				
			}
		}
    }

    public function editaAction()
    {
        // action body
        $request = $this->getRequest();
        $idGrupo = $this->getParam("idGrupo");
        if($request->isPost()){
            
            //print_r($idGrupo);
            //print_r("<br />");
            //print_r($request->getPost());
            $this->grupoDAO->editGrupo($idGrupo, $request->getPost());
            /*
            $post = $request->getPost();
            unset($post["submit"]);
            
            //$this->encuestaDAO->editarEncuesta($idEncuesta, $post);
            $this->grupoDAO->editarGrupo($idGrupo, $post);
            $this->_helper->redirector->gotoSimple("admin", "grupo", "encuesta", array("idGrupo" => $idGrupo));
            */
        }
        
        $this->_helper->redirector->gotoSimple("admin", "grupo", "encuesta",array("idGrupo"=>$idGrupo));
    }

    public function bajaAction()
    {
        // action body
        $idGrupo = $this->getParam("idGrupo");
		$this->grupoDAO->eliminarGrupo($idGrupo);
		$this->_helper->redirector->gotoSimple("index", "index", "encuesta");
    }

    public function opcionesAction()
    {
        // action body
        $request = $this->getRequest();
        $idGrupo = $this->getParam("idGrupo");
		$grupo = $this->grupoDAO->obtenerGrupo($idGrupo);
		$formulario = new Encuesta_Form_AltaSeleccion;
		if($request->isGet()){
			$this->view->grupo = $grupo;
			$this->view->formulario = $formulario;
			
		}elseif($request->isPost()){
			if($formulario->isValid($request->getPost())){
				
				$categorias = $formulario->getValues();
				$opciones = array();
				
				foreach ($categorias as $categoria) {
					foreach ($categoria as $elemento) {
						if(!is_null($elemento)){
							foreach ($elemento as $indice => $id) {
								$opciones[] = $id;
							}
						}
					}
				}
				
				//$this->opcionDAO->asociarOpcionesPregunta($idPregunta, $opciones);
				$this->opcionDAO->asociarOpcionesGrupo($idGrupo, $opciones);
				$this->_helper->redirector->gotoSimple("admin", "grupo", "encuesta", array("idGrupo"=>$idGrupo));
			}
		}
    }


}












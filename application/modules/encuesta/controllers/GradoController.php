<?php

class Encuesta_GradoController extends Zend_Controller_Action
{
	private $cicloDAO = null;
    private $nivelDAO = null;
    private $gradoDAO = null;
    private $materiaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
        $this->nivelDAO = new Encuesta_DAO_Nivel($dataIdentity["adapter"]);
        $this->gradoDAO = new Encuesta_DAO_Grado($dataIdentity["adapter"]);
		$this->materiaDAO = new Encuesta_DAO_Materia($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $idNivel = $this->getParam("idNivel");
		$nivel = $this->nivelDAO->obtenerNivel($idNivel);
		$grados = $this->gradoDAO->getGradosByIdNivel($idNivel);
		
		$this->view->nivel = $nivel;
		$this->view->grados = $grados;
    }

    public function adminAction()
    {
        // action body
        $idGrado = $this->getParam("idGrado");
		$grado = $this->gradoDAO->getGradoById($idGrado);
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
		
		$formulario = new Encuesta_Form_AltaGrado;
		$formulario->getElement("gradoEducativo")->setValue($grado->getGradoEducativo());
		$formulario->getElement("abreviatura")->setValue($grado->getAbreviatura());
		$formulario->getElement("descripcion")->setValue($grado->getDescripcion());
		$formulario->getElement("submit")->setLabel("Actualizar Grado");
		$formulario->getElement("submit")->setAttrib("class", "btn btn-warning");
		
		$this->view->grado = $grado;
		$this->view->nivel = $nivel;
		$this->view->formulario = $formulario;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $idNivel = $this->getParam("idNivel");
		$nivel = $this->nivelDAO->obtenerNivel($idNivel);
		//$formulario = new Encuesta_Form_AltaGrado;
		$this->view->nivel = $nivel;
		//$this->view->formulario = $formulario;
		if($request->isPost()){
		    $datos = $request->getPost();
            $datos["idNivelEducativo"] = $idNivel;
            $datos["fecha"] = date("Y-m-d H:i:s",time());
            $grado = new Encuesta_Models_GradoEducativo($datos);
		    //print_r($datos);
            try {
                $this->gradoDAO->addGrado($grado);
                $this->view->messageSuccess = "Grado: <strong>".$grado->getGradoEducativo()."</strong> dado de alta al Nivel: <strong>".$nivel->getNivel()."</strong> exitosamente.";
            } catch(Util_Exception_BussinessException $ex) {
                $this->view->messageFail = $ex->getMessage();
            }
			/*
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				$datos["idNivelEducativo"] = $idNivel;
				
				try {
					$this->gradoDAO->crearGrado($datos);
					$this->view->messageSuccess = "Grado: <strong>".$datos["gradoEducativo"]."</strong> dado de alta al Nivel: <strong>".$nivel["nivelEducativo"]."</strong> exitosamente.";
				} catch(Util_Exception_BussinessException $ex) {
					$this->view->messageFail = $ex->getMessage();
				}
				
			}
            */
		}
    }

    public function editaAction()
    {
        // action body
        $idGrado = $this->getParam("idGrado");
        $request = $this->getRequest();
		$post = $request->getPost();
		//unset($post['submit']);
		$this->gradoDAO->editarGrado($idGrado, $post);
		$this->_helper->redirector->gotoSimple("admin", "grado", "encuesta",array("idGrado"=>$idGrado));
    }

    public function bajaAction()
    {
        // action body
    }

    public function gradosAction()
    {
        // action body
        $idNivel = $this->getParam("idNivel");
        $nivel = $this->nivelDAO->obtenerNivel($idNivel);
        $grados = $this->gradoDAO->getGradosByIdNivel($idNivel);
        
        $this->view->nivel = $nivel;
        $this->view->grados = $grados;
    }

    public function materiasAction()
    {
        // action body
        $idGrado = $this->getParam("idGrado");
		$grado = $this->gradoDAO->getGradoById($idGrado);//->obtenerGrado($idGrado);
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
		//$ciclo = $this->cicloDAO->obtenerCiclo($grado->get)
		//$formulario = new Encuesta_Form_AltaMateria;
		//$materias = $this->materiaDAO->obtenerMateriasGrado($idGrado);
		$materias = $this->materiaDAO->getMateriasByIdGradoAndCurrentCiclo($idGrado);
		
		$this->view->nivel = $nivel;
		$this->view->grado = $grado;
		//$this->view->formulario = $formulario;
		$this->view->materias = $materias;
    }


}














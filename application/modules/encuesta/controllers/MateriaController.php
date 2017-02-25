<?php

class Encuesta_MateriaController extends Zend_Controller_Action
{
	private $materiaDAO = null;
    private $cicloDAO = null;
    private $gradoDAO = null;
	private $nivelDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->materiaDAO = new Encuesta_DAO_Materia($dataIdentity["adapter"]);
		$this->cicloDAO = new Encuesta_DAO_Ciclo($dataIdentity["adapter"]);
		$this->gradoDAO = new Encuesta_DAO_Grado($dataIdentity["adapter"]);
		$this->nivelDAO = new Encuesta_DAO_Nivel($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
    }

    public function adminAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
		//$idCiclo = $this->getParam("idCiclo");
		$idGrado = $this->getParam("idGrado");
		//$grado = $this->gradoDAO->obtenerGrado($idGrado);
		$grado = $this->gradoDAO->getGradoById($idGrado);
		$nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
		//$this->view->ciclo = $this->cicloDAO->obtenerCiclo($idCiclo);
		
		//$grado = $this->gradoDAO->obtenerGrado($idGrado);
		
        $formulario = new Encuesta_Form_AltaMateria;
		$formulario->getElement("idNivel")->clearMultiOptions();
		$formulario->getElement("idNivel")->addMultiOption($nivel->getIdNivel(),$nivel->getNivel());
		$formulario->getElement("idGrado")->clearMultiOptions();
		$formulario->getElement("idGrado")->addMultiOption($grado->getIdGradoEducativo(),$grado->getGradoEducativo());
		
		$this->view->grado = $grado;
		$this->view->formulario = $formulario;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				//print_r($datos);
				$materia = new Encuesta_Model_Materia($datos);
				try{
					$this->materiaDAO->crearMateria($materia);
					$this->view->messageSuccess = "Materia: <strong>" . $materia->getMateria() . "</strong> dada de alta exitosamente";
				}catch(Util_Exception_BussinessException $ex){
					//print_r($ex->__toString());
					$this->view->messageFail = $ex->getMessage();
				}
				
				//$this->view->datos = $datos;
			}
		}
    }

    public function consultaAction()
    {
        // action body
        $request = $this->getRequest();
        $formulario = new Encuesta_Form_ConsultaMateria;
		$this->view->formulario = $formulario;
		$this->view->materias = array();
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				
				$materias = $this->materiaDAO->obtenerMaterias($datos["idCiclo"], $datos["idGrado"]);
				
				$this->view->ciclo = $this->cicloDAO->obtenerCiclo($datos["idCiclo"]);
				$this->view->nivel = $this->nivelDAO->obtenerNivel($datos["idNivel"]);
				$this->view->grado = $this->gradoDAO->obtenerGrado($datos["idGrado"]);
				$this->view->materias = $materias;
			}
		}
    }


}








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
		$formulario->getElement("idNivelEducativo")->clearMultiOptions();
		$formulario->getElement("idNivelEducativo")->addMultiOption($nivel->getIdNivel(),$nivel->getNivel());
		$formulario->getElement("idGradoEducativo")->clearMultiOptions();
		$formulario->getElement("idGradoEducativo")->addMultiOption($grado->getIdGradoEducativo(),$grado->getGradoEducativo());
		
		$this->view->grado = $grado;
		$this->view->formulario = $formulario;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				try{
					$this->materiaDAO->crearMateria($datos);
					$this->view->messageSuccess = "Materia: <strong>" . $materia->getMateriaEscolar() . "</strong> dada de alta exitosamente";
				}catch(Exception $ex){
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
        $this->view->materias = array();
		
		if($request->isPost()){
		    $datos = $request->getPost();
		    
		    $materias = $this->materiaDAO->obtenerMaterias($datos["idCiclo"], $datos["idGrado"]);
		    
		    $this->view->ciclo = $this->cicloDAO->obtenerCiclo($datos["idCiclo"]);
		    $this->view->nivel = $this->nivelDAO->obtenerNivel($datos["idNivel"]);
		    $this->view->grado = $this->gradoDAO->obtenerGrado($datos["idGrado"]);
		    $this->view->materias = $materias;
		}
    }


}








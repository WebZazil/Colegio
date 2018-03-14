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
		$idGrado = $this->getParam("gr");
		
		$grado = $this->gradoDAO->getGradoById($idGrado);
		$nivel = $this->nivelDAO->obtenerNivel($grado['idNivelEducativo']);
		$ciclo = $this->cicloDAO->getCurrentCiclo();
		
		$this->view->grado = $grado;
		$this->view->nivel = $nivel;
		$this->view->ciclo = $ciclo;
		
		if($request->isPost()){
		    $datos = $request->getPost();
		    $datos['creacion'] = date('Y-m-d H:i:s');
		    //print_r($datos);
		    try{
		        $this->materiaDAO->crearMateria($datos);
		        $this->view->messageSuccess = "Materia: <strong>" . $datos['materiaEscolar'] . "</strong> dada de alta exitosamente";
		    }catch(Exception $ex){
		        $this->view->messageFail = $ex->getMessage();
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








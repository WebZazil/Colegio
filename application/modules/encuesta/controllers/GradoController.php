<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
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
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
		$this->cicloDAO = new Encuesta_Data_DAO_CicloEscolar($identity['adapter']);
		$this->nivelDAO = new Encuesta_Data_DAO_NivelEducativo($identity['adapter']);
		$this->gradoDAO = new Encuesta_Data_DAO_GradoEducativo($identity['adapter']);
		$this->materiaDAO = new Encuesta_Data_DAO_Materia($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $idNivel = $this->getParam("ne");
        
		$nivel = $this->nivelDAO->getNivelEducativoById($idNivel);
		//$grados = $this->gradoDAO->getGradosByIdNivel($idNivel);
		$grados = $this->gradoDAO->getGradosEducativosByIdNivelEscolar($idNivel);
		
		$this->view->nivel = $nivel;
		$this->view->grados = $grados;
    }

    public function adminAction()
    {
        // action body
        $idGrado = $this->getParam("gr");
		$grado = $this->gradoDAO->getGradoEducativoById($idGrado);
		$nivel = $this->nivelDAO->getNivelEducativoById($grado['idNivelEducativo']);
		
		$formulario = new Encuesta_Form_AltaGrado;
		$formulario->getElement("gradoEducativo")->setValue($grado['idGradoEducativo']);
		$formulario->getElement("abreviatura")->setValue($grado['abreviatura']);
		$formulario->getElement("descripcion")->setValue($grado['descripcion']);
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
        $idNivel = $this->getParam("ne");
		
        $nivel = $this->nivelDAO->getNivelEducativoById($idNivel);
		$this->view->nivel = $nivel;
		
		if($request->isPost()){
		    $datos = $request->getPost();
            $datos["idNivelEducativo"] = $idNivel;
            $datos["creacion"] = date("Y-m-d H:i:s",time());
            $grado = new Encuesta_Models_GradoEducativo($datos);
		    //print_r($datos);
            try {
                $this->gradoDAO->addGradoEducativo($datos);
                //$this->gradoDAO->addGrado($grado);
                $this->view->messageSuccess = "Grado: <strong>".$datos['gradoEducativo']."</strong> dado de alta al Nivel: <strong>".$nivel['nivelEducativo']."</strong> exitosamente.";
            } catch(Exception $ex) {
                $this->view->messageFail = $ex->getMessage();
            }
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
        $idNivel = $this->getParam("ni");
        
        $nivel = $this->nivelDAO->getNivelEducativoById($idNivel);
        $grados = $this->gradoDAO->getAllGradosEducativosByIdNivelEducativo($idNivel);
        
        $this->view->nivel = $nivel;
        $this->view->grados = $grados;
    }

    public function materiasAction()
    {
        // action body
        $idGrado = $this->getParam("idGrado");
        
		$grado = $this->gradoDAO->getGradoEducativoById($idGrado);
		$nivel = $this->nivelDAO->getNivelEducativoById($grado['idNivelEducativo']);
		$ciclo = $this->cicloDAO->getCicloEscolarActual();
		
		$materias = $this->materiaDAO->getMateriasByIdGradoEducativoAndIdCicloEscolar($idGrado, $ciclo['idCicloEscolar']);
		
		$this->view->nivel = $nivel;
		$this->view->grado = $grado;
		$this->view->materias = $materias;
    }

    public function gruposAction()
    {
        // action body
        $idGrado = $this->getParam('idGrado');
        $grado = $this->gradoDAO->getGradoEducativoById($idGrado);
        $grupos = $this->gradoDAO->getGruposEscolaresByIdGradoEducativo($idGrado);
        
        //print_r($grupos);
        $this->view->grado = $grado;
        $this->view->grupos = $grupos;
    }


}
















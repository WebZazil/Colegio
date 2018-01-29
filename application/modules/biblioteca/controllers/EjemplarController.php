<?php

class Biblioteca_EjemplarController extends Zend_Controller_Action
{

    private $recursoDAO = null;

    private $ejemplarDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            ;
        }
        $identity = $auth->getIdentity();
        
        //$dbAdapter = Zend_Registry::get("dbmodqueryb");
        //$this->ejemplarDAO = new Biblioteca_DAO_Ejemplar($identity['adapter']);
        $this->ejemplarDAO = new Biblioteca_Data_DAO_Ejemplar($identity['adapter']);
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idRecurso = $this->getParam('rc');
        $this->view->recurso = $this->recursoDAO->getRecursoById($idRecurso);
        
        $ejemplarDAO = $this->ejemplarDAO;
		
		$this->view->tiposLibro = $ejemplarDAO->getAllRowsTiposLibro();
		$this->view->editoriales = $ejemplarDAO->getAllRowsEditoriales();
		$this->view->idiomas = $ejemplarDAO->getAllRowsIdiomas();
		$this->view->paises = $ejemplarDAO->getAllRowsPaises();
		$this->view->series = $ejemplarDAO->getAllRowsSeries();
		
		if($request->isPost()){
		    $datos = $request->getPost();
		    $datos['idRecurso'] = $idRecurso;
		    //$datos['idDimensionesEjemplar'] = 0;
		    $datos['creacion'] = date('Y-m-d H:i:s',time());
		    //print_r($datos);
		    
		    $dimEjem = array(
		        'largo' => $datos['largo'],
		        'alto' => $datos['alto'],
		        'ancho' => $datos['ancho'],
		        'creacion' => date('Y-m-d H:i:s',time())
		    );
		    
		    unset($datos['largo']);
		    unset($datos['alto']);
		    unset($datos['ancho']);
		    
		    try{
		        $idDimEjem = $ejemplarDAO->altaDimensionesEjemplar($dimEjem);
		        $datos['idDimensionesEjemplar'] = $idDimEjem;
		        $idEjemplar = $ejemplarDAO->altaEjemplar($datos);
		        $this->view->messageSuccess ="Ejemplar ha sido agregado";
		    }catch(Exception $ex){
		        $this->view->messageFail = "Ejemplar no ha sido agregado.<br /> Error: <strong>".$ex->getMessage()."</strong>";
		    }
		}
    }

    public function adminAction()
    {
        // action body
        $idEjemplar = $this->getParam('ej');
    }

    public function ejemplaresAction()
    {
        // action body
        $idRecurso = $this->getParam('rc');
        $recurso = $this->recursoDAO->getRecursoById($idRecurso);
        $ejemplares = $this->ejemplarDAO->getObjectEjemplaresRecurso($idRecurso);
        
        $this->view->recurso = $recurso;
        $this->view->ejemplares = $ejemplares;
    }

    public function acopiaAction() {
        // action body
        $request = $this->getRequest();
        $idEjemplar = $this->getParam('ej');
        $ejemplar = $this->ejemplarDAO->getObjectEjemplar($idEjemplar);
        
        $recurso = $this->recursoDAO->getRecursoById($ejemplar['ejemplar']['idRecurso']);
        $estatusEjemplar = $this->ejemplarDAO->getAllRowsEstatusEjemplar();
        //print_r($recurso);
        $copias = $this->ejemplarDAO->getCopiasEjemplar($idEjemplar);
        
        $this->view->ejemplar = $ejemplar;
        $this->view->recurso = $recurso;
        $this->view->estatusEjemplar = $estatusEjemplar;
        $this->view->copias = count($copias);
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos['idEjemplar'] = $idEjemplar;
            $datos['creacion'] = date('Y-m-d H:i:s', time());
            print_r($datos);
            print_r('<br /><br />');
            
            try {
                $this->ejemplarDAO->altaCopiaEjemplar($datos);
                $this->view->messageSuccess = 'Copia de Ejemplar dada de alta correctamente';
            } catch (Exception $e) {
                print_r($e->getMessage());
                $this->view->messageFail = 'Error al agregar la copia:<br /> <strong>'.$e->getMessage().'</strong>';
            }
            
        }
    }


}

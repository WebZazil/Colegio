<?php

class Encuesta_CategoriaController extends Zend_Controller_Action
{

	private $categoriaDAO = null;
	private $opcionDAO = null;
	
    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        
        $this->categoriaDAO = new Encuesta_DAO_Categoria($dataIdentity["adapter"]);
		$this->opcionDAO = new Encuesta_DAO_Opcion($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
        $this->view->categorias = $this->categoriaDAO->obtenerCategorias();
    }

    public function adminAction()
    {
        // action body
        $idCategoria = $this->getParam("idCategoria");
		$categoria = $this->categoriaDAO->obtenerCategoria($idCategoria);
		$opciones = $this->categoriaDAO->obtenerOpciones($idCategoria);
		
		$formulario = new Encuesta_Form_AltaCategoria();
		
		$formulario->getElement("categoria")->setValue($categoria["categoria"]);
		$formulario->getElement("descripcion")->setValue($categoria["descripcion"]);
		$formulario->getElement("submit")->setLabel("Actualizar Categoria");
		$formulario->getElement("submit")->setAttrib("class", "btn btn-warning");
		
		$this->view->categoria = $categoria;
		$this->view->opciones = $opciones;
		$this->view->formulario = $formulario;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            $datos["fecha"] = date("Y-m-d H:i:s", time());
            
            //print_r($datos);
            
            try{
                $this->categoriaDAO->crearCategoria($datos);
                $this->view->messageSuccess = "La categoría <strong>".$datos["categoria"]."</strong> ha sido creada exitosamente.";
            }catch(Exception $ex){
                $this->view->messageFail = $ex->getMessage();
            }
        }
    }

    public function editaAction()
    {
        // action body
        $request = $this->getRequest();
		$idCategoria = $this->getParam("idCategoria");
        $post = $request->getPost();
		//eliminamos este elemento pues genera errores por que se toma como columna a editar
        unset($post["submit"]);
		$this->categoriaDAO->editarCategoria($idCategoria, $post);
		$this->_helper->redirector->gotoSimple("index", "categoria", "encuesta");
    }

    public function opcionesAction()
    {
        // action body
        $idCategoria = $this->getParam("idCategoria");
		$categoria = $this->categoriaDAO->obtenerCategoria($idCategoria);
		$opciones = $this->categoriaDAO->obtenerOpciones($idCategoria);
		
		$this->view->categoria = $categoria;
		$this->view->opciones = $opciones;
		//$this->view->formulario = $formulario;
    }


}










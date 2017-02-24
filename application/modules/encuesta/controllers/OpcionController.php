<?php

class Encuesta_OpcionController extends Zend_Controller_Action
{

    private $opcionDAO = null;
    private $categoriaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $this->opcionDAO = new Encuesta_DAO_Opcion($dataIdentity["adapter"]);
		$this->categoriaDAO = new Encuesta_DAO_Categoria($dataIdentity["adapter"]);
    }

    public function indexAction()
    {
        // action body
    }

    public function adminAction()
    {
        // action body
        $idOpcion = $this->getParam("idOpcion");
		$opcion = $this->opcionDAO->obtenerOpcion($idOpcion);
		$this->view->opcion = $opcion;
		$formulario = new Encuesta_Form_AltaOpcion;
		$formulario->getElement("submit")->setLabel("Actualiza Opcion");
		
		$this->view->formulario = $formulario;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
		$idCategoria = $this->getParam("idCategoria");
		//$categoria = $this->categoriaDAO->obtenerCategoria($idCategoria);
		$this->view->categoria = $this->categoriaDAO->obtenerCategoria($idCategoria);
		
		if ($request->isPost()) {
			$datos = $request->getPost();
            $datos["idCategoriasRespuesta"] =$idCategoria;
                
            switch ($datos["tipoValor"]) {
                case 'EN':
                    $datos["valorEntero"] = $datos["valor"];
                    $datos["valorTexto"] = null;
                    $datos["valorDecimal"] = null;
                    break;
                case 'TX':
                    $datos["valorEntero"] = null;
                    $datos["valorTexto"] = $datos["valor"];
                    $datos["valorDecimal"] = null;
                    break;
                case 'DC':
                    $datos["valorEntero"] = null;
                    $datos["valorTexto"] = null;
                    $datos["valorDecimal"] = $datos["valor"];
                    break;
            }
            
            $datos["fecha"] = date("Y-m-d H:i:s",time());
            unset($datos["valor"]);
            //print_r($datos);
            try{
                $this->opcionDAO->crearOpcion($idCategoria, $datos);
                $this->view->messageSuccess = "Opcion: <strong>".$datos["nombreOpcion"]."</strong> dada de alta exitosamente en el sistema";
            }catch(Util_Exception_BussinessException $ex){
                $this->view->messageFail = $ex->getMessage();
            }
		}
		
		/*
		if($request->isPost()){
			if($formulario->isValid($request->getPost())) {
				$datos = $formulario->getValues();
				$datos["idCategoriasRespuesta"] =$idCategoria;
				
				switch ($datos["tipoValor"]) {
					case 'EN':
						$datos["valorEntero"] = $datos["valor"];
						break;
					case 'TX':
						$datos["valorTexto"] = $datos["valor"];
						break;
					case 'DC':
						$datos["valorDecimal"] = $datos["valor"];
						break;
				}
				unset($datos["valor"]);
				
				try{
					$this->opcionDAO->crearOpcion($idCategoria, $datos);
					$this->view->messageSuccess = "Opcion: <strong>".$datos["nombreOpcion"]."</strong> dada de alta exitosamente en el sistema";
				}catch(Util_Exception_BussinessException $ex){
					$this->view->messageFail = $ex->getMessage();
				}
			}
		}
        */
    }

    public function avalorAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idOpcion = $this->getParam("idOpcion");
		$idCategoria = $this->getParam("idCategoria");
		
		$opcion = $this->opcionDAO->obtenerOpcion($idOpcion);
		$categoria = $this->categoriaDAO->obtenerCategoria($idCategoria);
		
		$this->view->opcion = $opcion;
		$this->view->categoria = $categoria;
		
		$formulario = new Encuesta_Form_AltaValor;
		
		$this->view->formulario = $formulario;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				$datos["idOpcion"] = $idOpcion;
				
				try{
					$this->opcionDAO->asignarValorOpcion($idOpcion, $datos);
					$this->view->messageSuccess = "Valor: <strong>".$datos["valor"]." asignado a la Opcion: <strong>".$opcion->getOpcion()."</strong> exitosamente !!";
				}catch(Util_Exception_BussinessException $ex){
					$this->view->messageFail = $ex->getMessage();
				}
			}
		}
    }

    public function editaAction()
    {
        // action body
    }


}










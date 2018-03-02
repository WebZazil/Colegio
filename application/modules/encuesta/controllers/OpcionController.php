<?php

class Encuesta_OpcionController extends Zend_Controller_Action
{

    private $opcionDAO = null;
    private $categoriaDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        
        $this->categoriaDAO = new Encuesta_Data_DAO_Categoria($identity["adapter"]);
        $this->opcionDAO = new Encuesta_Data_DAO_OpcionCategoria($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function adminAction()
    {
        // action body
        $request = $this->getRequest();
        $idOpcion = $this->getParam("op");
		
		if ($request->isPost()) {
		    $datos = $request->getPost();
		    //print_r($datos);
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
		    
		    unset($datos['valor']);
		    
		    try {
		        $this->opcionDAO->updateOpcion($idOpcion, $datos);
		    } catch (Exception $e) {
		        print_r($e->getMessage());
		    }
		}
		
		$opcion = $this->opcionDAO->getOpcionById($idOpcion);
		$categoria = $this->categoriaDAO->getCategoriaById($opcion['idCategoriasRespuesta']);
		
		$this->view->opcion = $opcion;
		$this->view->categoria = $categoria;
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
		$idCategoria = $this->getParam("ca");
		
		$categoria = $this->categoriaDAO->getCategoriaById($idCategoria);
		$this->view->categoria = $categoria;
		
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
            
            $datos['orden'] = count(explode(',', $categoria['idsOpciones']));
            $datos["creacion"] = date("Y-m-d H:i:s",time());
            unset($datos["valor"]);
            //print_r($datos);
            try{
                $this->opcionDAO->addOpcionCategoria($datos);
                $this->categoriaDAO->normalizeCategoria($idCategoria);
                $this->view->messageSuccess = "Opcion: <strong>".$datos["nombreOpcion"]."</strong> dada de alta exitosamente en el sistema";
            }catch(Exception $ex){
                $this->view->messageFail = $ex->getMessage();
            }
		}
    }

    /**
     * Normalizamos las opciones mayor y menor para categoria
     */
    public function normalizeAction()
    {
        // action body
        $idCategoria = $this->getParam('ca');
        $opciones = $this->opcionDAO->getOpcionesByIdCategoria($idCategoria);
        
        $valMax = 0;
        $valMin = 1000;
        
        $idValMax = '';
        $idValMin = '';
        
        foreach ($opciones as $opcion){
            $tValor = $opcion['tipoValor'];
            $valOpt = '';
            
            switch ($tValor){
                case 'EN' : $valOpt = $opcion['valorEntero'];
                    break;
                case 'DC' : $valOpt = $opcion['valorDecimal'];
                    break;
            }
            
            if ($valOpt > $valMax) {
                $valMax = $valOpt;
                $idValMax = $opcion['idOpcionCategoria'];
            }
            
            if ($valOpt < $valMin) {
                $valMin = $valOpt;
                $idValMin = $opcion['idOpcionCategoria'];
            }
        }
        
        //print_r('Id Max: '.$idValMax);
        //print_r('Id Min: '.$idValMin);
        
        $datos = array(
            'idOpcionMayor' => $idValMax,
            'idOpcionMenor' => $idValMin
        );
        
        $this->categoriaDAO->updateCategoria($idCategoria, $datos);
        $this->opcionDAO->reordenarOpciones($idCategoria);
        $this->_helper->redirector->gotoSimple("admin", "categoria", "encuesta",array('ca'=>$idCategoria));
    }


}


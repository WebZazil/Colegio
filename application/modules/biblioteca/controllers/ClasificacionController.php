<?php

class Biblioteca_ClasificacionController extends Zend_Controller_Action
{
	private $clasificacionDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($dbAdapter);
    }

    public function indexAction()
    {
        // action body
      //  $clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
		//$this->view->clasificaciones = $clasificaciones;
		
        $clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        
        $this->view->clasificaciones = $clasificaciones;
        $request = $this->getRequest();
        
        if($request->isPost()){
            $clasificaciones =$request->getPost();
            print_r($clasificaciones); print_r("<br />");
            
            
            foreach ($clasificaciones as $key => $value){
                if($value == "0"){
                    unset($clasificacion[$key]);
                }
            }
            
            $resources = $this->clasificacionDAO->getEditorialByParamas($clasificaciones);
            if(!empty($resources)){
                
                $container = array();
                
                $container = $resources;
                $this->view->resources = $container;
                
            }else{
                $this->view->resources = $array;
            }
            
            
            
        }else{
            $this->view->resources = array();
        }
        
        $clasificacionDAO   = $this->clasificacionDAO;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaClasificacion;
        $this->view->formulario = $formulario;
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    $this->clasificacionDAO->addClasificacion($data);
                    $this->view->messageSuccess = "Clasificación dada de de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de clasificación <strong>".$ex->getMessage()."</strong>";
                }
            }
        }
    }

    public function editaAction()
    {
        // action body
    }

    public function adminAction()
    {
        // action body
    }


}








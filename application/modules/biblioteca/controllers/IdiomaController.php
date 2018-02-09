<?php

class Biblioteca_IdiomaController extends Zend_Controller_Action
{
	
	private $idiomaDAO;
	
    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if(! $auth->hasIdentity()){
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        
        $identity = $auth->getIdentity();
        
        //$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->idiomaDAO = new Biblioteca_Data_DAO_Idioma($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        $idiomas = $this->idiomaDAO->getAllIdiomas();
        $this->view->idiomas = $idiomas;
    }

    public function altaAction()
    {
        // action body
        
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaIdioma;
        $this->view->formulario = $formulario;
		
		if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                	
					 $this->idiomaDAO->agregarIdioma($datos);
                    $this->view->messageSuccess = "Idioma dado de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de editorial <strong>".$ex->getMessage()."</strong>";
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
        
        $request = $this->getRequest();
        $idIdioma = $this->getParam('idm');
        
        //print_r($idClasificacion);
        
        $idiomas = $this->idiomaDAO->getIdiomaById($idIdioma);
        
        
        $this->view->idiomas = $idiomas;
        
        if($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                
                $idIdioma = $this->idiomaDAO->editarIdioma($idIdioma, $datos);
                $this->view->messageSuccess ="Idioma: <strong>".$datos['idioma']."</strong> ha sido modificado";
                
            }catch (Exception $ex){
                $this->view->messageFail = "El idioma: <strong>".$datos['idioma']."</strong> no ha sido modificado. Error: <strong>".$ex->getMessage()."<strong>";
            }
            
        }
    
    }


}








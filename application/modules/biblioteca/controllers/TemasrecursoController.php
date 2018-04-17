<?php

class Biblioteca_TemasrecursoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        
        $auth = Zend_Auth::getInstance();
        if(! $auth->hasIdentity()){
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        
        $identity = $auth->getIdentity();
        
        $this->temasRecursoDAO = new Biblioteca_DAO_Temasrecurso($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaLibrosTema();
        
        if ($request->isGet()) {
            $this->view->formulario = $formulario;
        }elseif ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                
                //$librosTema = new Biblioteca_Model_Librostema($datos);
                
                try{
                    //$this->libroTemaDAO->agregarLibrosTema($librosTema);
                    $this->temasRecursoDAO->agregarLibrosTema($datos);
                    $this->view->messageSuccess = "Exito en la inserción";
                }catch(Exception $ex){
                    $this->view->messageFail = "Fallo al insertar en la BD Error:".$ex->getMessage()."<strong>";
                }
            }
        }
    }


}




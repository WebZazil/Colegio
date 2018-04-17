<?php

class Biblioteca_SubdivisionestemaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");;
        }
        $identity = $auth->getIdentity();
        
        $this->subdivisionesTDAO = new Biblioteca_DAO_SubdivisionesTema($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
        
        $formulario = new Biblioteca_Form_AltaTemasSubdivision();
        
        if ($request->isGet()) {
            
            $this->view->formulario = $formulario;
        }elseif ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                
               // $temasSubdivision = new Biblioteca_Model_TemasSubdivision($datos);
                
                try{
                    $this->subdivisionesTDAO->agregarSubdivisionesT($datos);
                    $this->view->messageSuccess = "Exito en la inserción";
                }catch(Exception $ex){
                    $this->view->messageFail = "Fallo al insertar en la BD Error:".$ex->getMessage()."<strong>";
                }
            }
        }
    }


}




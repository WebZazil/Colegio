<?php

class Biblioteca_AutorController extends Zend_Controller_Action
{
    private $autorDAO;
    

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodqueryb");
                
        $this->autorDAO = new Biblioteca_Data_DAO_Autor($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        $autores = $this->autorDAO->getAllAutores();
        $this->view->autores = $autores;
    }

    public function altaAction() {
        // action body
        $request = $this->getRequest();
        $formulario = new Biblioteca_Form_AltaAutor;
        $this->view->formulario = $formulario;
        
        if ($request->isPost()) {
            if ($formulario->isValid($request->getPost())) {
                $datos = $formulario->getValues();
                $datos["creacion"] = date("Y-m-d h:i:s",time());
                //print_r($datos);
                
                try{
                    switch ($datos["tipo"]) {
                        case 'UN':
                            $datos["autores"] = "";
                            break;
                        case 'VR':
                        case 'IN':
                            $datos["nombres"] = "";
                            $datos["apellidos"] = "";
                            if ($datos["autores"] == "") $datos["autores"] = "Indefinido";
                            break;
                    }
                    
                    $this->autorDAO->addAutor($datos);
                    $this->view->messageSuccess = "Autor(es) dado(s) de alta exitosamente.";
                }catch(Exception $ex){
                    $this->view->messageFail = "Error en alta de autor(es) <strong>".$ex->getMessage()."</strong>";
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








<?php

class Biblioteca_IndexController extends Zend_Controller_Action
{

    private $serviceLogin = null;

    private $testConnector = null;

    private $loginDAO = null;

    private $recursoDAO = null;

    private $materialDAO = null;

    private $coleccionDAO = null;

    private $clasificacionDAO = null;

    private $autorDAO = null;

    private $inventarioDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeBiblioteca');
        
        $this->serviceLogin = new Biblioteca_Service_Login();
        
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $this->testConnector = $this->serviceLogin->getTestConnection($testData, 'colsagcor16', 'MOD_BIBLIOTECA');
        
        $this->loginDAO = new Biblioteca_Data_DAO_Login($this->testConnector); 
        $this->recursoDAO = new Biblioteca_Data_DAO_Recurso($this->testConnector);
        $this->materialDAO = new Biblioteca_Data_DAO_Material($this->testConnector);
        $this->coleccionDAO = new Biblioteca_Data_DAO_Coleccion($this->testConnector);
        $this->clasificacionDAO = new Biblioteca_Data_DAO_Clasificacion($this->testConnector);
        $this->autorDAO = new Biblioteca_Data_DAO_Autor($this->testConnector);
        
        $this->inventarioDAO = new Biblioteca_Data_DAO_Inventario($this->testConnector);
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        
        $this->view->materiales = $this->materialDAO->getAllMateriales();
        $this->view->colecciones = $this->coleccionDAO->getAllColecciones();
        $this->view->clasificaciones = $this->clasificacionDAO->getAllClasificaciones();
        $this->view->recursos = array();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos); print_r('<br /><br />');
            foreach ($datos as $key => $value) {
                if ($value == "0" || $value == '') {
                    unset($datos[$key]);
                }
            }
            
            $recursos = $this->recursoDAO->getRecursoByParams($datos);
            //print_r($datos);
            $contenedor = array();
            
            foreach ($recursos as $recurso) {
                $obj = $this->inventarioDAO->getObjectRecurso($recurso['idRecurso']);
                $contenedor[] = $obj;
            }
            
            $this->view->recursos = $contenedor;
        }
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            // print_r($datos);
            try{
                $this->serviceLogin->simpleLogin($datos, 'colsagcor16', 'MOD_BIBLIOTECA');
                $this->_helper->redirector->gotoSimple("index", "dashboard", "biblioteca");
            }catch(Exception $ex){
                //print_r($ex->getMessage());
                $this->view->errorMessage = $ex->getMessage();
            }
        }
    }

    public function loginuAction()
    {
        // action body
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            
            try{
                $this->loginDAO->loginUser($datos);
                $this->_helper->redirector->gotoSimple("index", "udashboard", "biblioteca");
            }catch(Exception $ex){
                //print_r($expression);
                $this->view->errorMessage = $ex->getMessage();
            }
        }
    }

    public function loginbAction()
    {
        // action body
    }

    public function testtAction()
    {
        // action body
    }


}










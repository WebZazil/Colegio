<?php

class ModuleController extends Zend_Controller_Action
{

    private $systemDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (is_null($auth)) {
            ;
        }
        $identity = $auth->getIdentity();
        $this->systemDAO = new App_Data_DAO_System($identity['adapter']);
        //print_r($identity);
    }

    public function indexAction()
    {
        // action body
        $identity = Zend_Auth::getInstance()->getIdentity();
        $organizacion = $identity['org'];
        $modules = $this->systemDAO->getModulesByIdOrganizacion($organizacion['idOrganizacion']);
        
        $this->view->modulos = $modules;
        $this->view->organizacion = $organizacion;
    }

    public function adirAction()
    {
        // action body
        $identity = Zend_Auth::getInstance()->getIdentity();
        $organizacion = $identity['org'];
        
        $request = $this->getRequest();
        
        $params = $this->getAllParams();
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        
        //print_r($params);
        $modulo = $this->systemDAO->getModule($params['md']);
        $tipoDirectorio = $params['to'];
        
        $this->view->modulo = $modulo;
        $this->view->tipoDirectorio = $tipoDirectorio;
        $this->view->organizacion = $organizacion;
        
        if ($request->isPost()) {
            
        }
        
    }


}




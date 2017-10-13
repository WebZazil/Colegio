<?php

class Evento_IndexController extends Zend_Controller_Action
{
    private $eventoDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        
        $dbAdapter = Zend_Registry::get('zbase');
        $this->eventoDAO = new Evento_Model_DAO_Evento($dbAdapter);
        
        $this->_helper->layout->setLayout('homeEvento');
    }

    public function indexAction()
    {
        // action body
        
        
        
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            // print_r($datos);
            
            $claveOrganizacion = 'colsagcor16';
            $organizacion = $this->eventoDAO->getOrganizacionByClave($claveOrganizacion);
            //print_r($organizacion); print_r("<br /><br />");
            $subscripciones = $this->eventoDAO->getSubscripcionesByIds($organizacion['idOrganizacion']);
            //print_r($subscripciones); print_r("<br /><br />");
            $subscripcion = $subscripciones[0];
            
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('zbase'),"Usuario","nickname","password",'SHA1(?)');
            $authAdapter->setIdentity($datos["usuario"])->setCredential($datos["password"]);
            
            $auth = Zend_Auth::getInstance();
            $resultado = $auth->authenticate($authAdapter);
            
            if ($resultado->isValid()) {
                //$info = $authAdapter->getResultRowObject(null,'password');
                
                $conn = array();
                $conn['host'] = $subscripcion['host'];
                $conn['dbname'] = $subscripcion['dbname'];
                $conn['username'] = $subscripcion['username'];
                $conn['password'] = $subscripcion['password'];
                $conn['charset'] = $subscripcion['charset'];
                
                $db = Zend_Db::factory(strtoupper($subscripcion["adapter"]), $conn);
                
                $userObj = array();
                $userObj['organizacion'] = $organizacion;
                $userObj['adapter'] = $db;
                
                $auth->getStorage()->write($userObj);
                
                $this->_helper->redirector->gotoSimple("index", "dashboard", "evento");
            }else{
                print_r($resultado->getMessages());
            }
        }else{
            $this->_helper->redirector->gotoSimple("index", "registro", "evento");
        }
    }

    public function logoutAction()
    {
        // action body
    }


}






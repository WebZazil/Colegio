<?php

class Soporte_IndexController extends Zend_Controller_Action
{
    private $loginDAO = null;
    private $equipoDAO = null;
    
    
    public function init()
    {
        /* Initialize action controller here */
        $this->loginDAO = new Soporte_DAO_Login();
        //$this->equipoDAO = new Soporte_Data_DAO_Equipo();
    }

    public function indexAction()
    {
        // action body
        $this->_helper->layout->setLayout('homeSoporte');
        //$equipoDAO = $this->equipoDAO;
        
        //$usuarios = $equipoDAO->getUsuarios();
        //$ubicaciones = $equipoDAO->getUbicaciones();
        //$this->view->usuarios = $usuarios;
        //$this->view->ubicaciones = $ubicaciones;
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        
        
        $this->_helper->redirector->gotoSimple("index", "index", "soporte");
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        
        if($request->isPost()){
            $datos = $request->getPost();
            //print_r($request->getPost());
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbmodadmin'),"Usuario","nickname","password",'SHA1(?)');
            if ($datos["usuario"] != '' && $datos["password"] != '') {
                $claveOrganizacion = 'colsagcor16';
                
                $authAdapter->setIdentity($datos['usuario'])->setCredential($datos['password']);
                $auth = Zend_Auth::getInstance();
                
                $organizacion = $this->loginDAO->getOrganizacionByClaveOrganizacion($claveOrganizacion);
                $subscripcion = $this->loginDAO->getSubscripcionByIdOrganizacion($organizacion["idOrganizacion"]);
                
                //print_r($organizacion); print_r("<br />");
                //print_r($subscripcion);
                
                $resultado = $auth->authenticate($authAdapter);
                if ($resultado->isValid()) {
                    //print_r('resultado valido');
                    
                    $usuario = $authAdapter->getResultRowObject(null);
                    
                    // Creamos contenedor de datos, aqui guardaremos la info de conexion dinamica.
                    $dc = array(); // dc dinamic connection
                    
                    $arrconn = array();
                    $n_adapter = $subscripcion["adapter"];
                    //$a = array();
                    $arrconn["host"] = $subscripcion["host"];
                    $arrconn["username"] = $subscripcion["username"];
                    $arrconn["password"] = $subscripcion["password"];
                    $arrconn["dbname"] = $subscripcion["dbname"];
                    $arrconn["charset"] = $subscripcion["charset"];
                    
                    $db = Zend_Db::factory(strtoupper($n_adapter),$arrconn);
                    
                    $dc['user'] = $usuario;
                    $dc['organizacion'] = $organizacion;
                    $dc['subscripcion'] = $subscripcion;
                    $dc['adapter'] = $db;
                    
                    $auth->getStorage()->write($dc);
                    
                }else{
                    // print_r('resultado invalido');
                    $this->view->message = 'Error on Login!!';
                    $this->_helper->redirector->gotoSimple("index", "index", "soporte");
                }
            }else{
                $this->view->message = 'Error on Login!!';
                $this->_helper->redirector->gotoSimple("index", "index", "soporte");
            }
            
            
        }
    }


}






<?php

class Soporte_IndexController extends Zend_Controller_Action
{
    private $loginDAO = null;
    private $equipoDAO = null;
    
    
    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (is_null($auth)) {
            $this->_helper->redirector->gotoSimple("index", "index", "soporte");
        }
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

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        
        if($request->isPost()){
            $datos = $request->getPost();
            //print_r($request->getPost());
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('zbase'),"Usuario","nickname","password",'SHA1(?)');
            if ($datos["usuario"] != '' && $datos["password"] != '') {
                $claveOrganizacion = 'colsagcor16';
                
                $authAdapter->setIdentity($datos['usuario'])->setCredential($datos['password']);
                $auth = Zend_Auth::getInstance();
                
                $organizacion = $this->loginDAO->getOrganizacionByClaveOrganizacion($claveOrganizacion);
                $subscripcion = $this->loginDAO->getQuerySubscripcionByOrganizacion($organizacion["idOrganizacion"]);
                
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
                    
                    $this->_helper->redirector->gotoSimple("index", "usuario", "soporte");
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






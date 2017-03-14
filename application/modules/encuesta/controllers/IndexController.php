<?php

class Encuesta_IndexController extends Zend_Controller_Action
{

    private $service = null;

    private $loginDAO = null;

    private $identity = null;

    private $cicloDAO = null;

    private $docenteDAO = null;

    private $nivelDAO = null;

    private $encuestaDAO = null;

    private $materiaDAO = null;

    private $registroDAO = null;

    private $evaluacionDAO = null;

    private $asignacionDAO = null;

    private $generador = null;

    private $grupoDAO = null;

    private $gradoDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeEncuesta');
		
		$auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        $this->identity = $dataIdentity;
        
        //print_r($this->identity["adapter"]);
        
        $this->service = new Encuesta_Util_Service;
        $this->loginDAO = new Encuesta_DAO_Login();
		$dbAdapter = Zend_Registry::get('dbmodquery');
        
        $this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($dbAdapter);
        $this->generador = new Encuesta_Util_Generator($dbAdapter);
        $this->cicloDAO = new Encuesta_DAO_Ciclo($dbAdapter);
		$this->nivelDAO = new Encuesta_DAO_Nivel($dbAdapter);
        $this->grupoDAO = new Encuesta_DAO_Grupos($dbAdapter);
        $this->gradoDAO = new Encuesta_DAO_Grado($dbAdapter);
        
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($dbAdapter);
        $this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
        $this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
        $this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
        //$this->docenteDAO = new Encuesta_DAO_Registro($this->identity["adapter"]);
        $this->_helper->layout->setLayout('homeEncuesta');
    }

    public function indexAction()
    {
        // action body
        $cicloDAO = $this->cicloDAO;
		$ciclos = $cicloDAO->getAllCiclos();
		//Niveles Educativos - Independiente de Ciclos escolares
		$niveles = $this->nivelDAO->obtenerNiveles();
		
		$this->view->ciclosEscolares = $ciclos;
		$this->view->nivelesEscolares = $niveles;
		// Iniciamos sesion con usuario 'test'
		$loginDAO = $this->loginDAO;
		$claveOrganizacion = 'colsagcor16';
        $organizacion = $loginDAO->getOrganizacionByClave($claveOrganizacion);
		$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbmodadmin'),"Usuario","nickname","password",'SHA1(?)');
        $authAdapter->setIdentity('test')->setCredential('zazil');
        $auth = Zend_Auth::getInstance();
        $resultado = $auth->authenticate($authAdapter);
		if ($resultado->isValid()) {
			$data = $authAdapter->getResultRowObject(null,'password');
            $subscripcion = $loginDAO->getSubscripcion($organizacion["idOrganizacion"]);
            //print_r($subscripcion);
            $adapter = $subscripcion["adapter"];
            //unset($subscripcion["adapter"]);
            // Creamos la conexion a la bd en la que vamos a operar
            $currentDbConnection = array();
            //$currentDbConnection["adapter"] = $subscripcion["adapter"];
            $currentDbConnection["host"] = $subscripcion["host"];
            $currentDbConnection["username"] = $subscripcion["username"];
            $currentDbConnection["password"] = $subscripcion["password"];
            $currentDbConnection["dbname"] = $subscripcion["dbname"];
            $currentDbConnection["charset"] = $subscripcion["charset"];
            //$adapter = new Zend_Db_Adapter_Abstract($currentDbConnection);
            //print_r("<br /><br />");
            //print_r($currentDbConnection);
            //print_r("<br /><br />");
            //Zend_Registry::set('dbmodencuesta', $adapter);
            //print_r(Zend_Registry::get('dbmodencuesta'));
            $db = Zend_Db::factory(strtoupper($adapter),$currentDbConnection);
            //Zend_Registry::set('dbmodencuesta', $db);
            //$dbAdapter = Zend_Registry::get('dbmodencuesta');
            //print_r("<br /><br />");
            //print_r($db);
            //print_r($data);
            $userInfo = array();
            $userInfo["user"] = $data;
            $userInfo["rol"] = $this->loginDAO->getRolbyId($data->idRol);
            $userInfo["organizacion"] = $organizacion;
            $userInfo["adapter"] = $db;
            //$userInfo["organizacion"] = $this->loginDAO->getOrganizacionByClave($datos["claveOrganizacion"]);
            $auth->getStorage()->write($userInfo);
		}
		
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
		if ($request->isPost()) {
			$loginDAO = $this->loginDAO;
	        //$this->_helper->layout->setLayout('emptyEncuesta');
	        // action body
	        $datos = $request->getPost();
			$claveOrganizacion = 'colsagcor16';
            $organizacion = $loginDAO->getOrganizacionByClave($claveOrganizacion);
            
            /*
            print_r($datos);
            print_r("<br />");
            print_r($organizacion);
            print_r("<br />");
            print_r($subscripcion);
            print_r("<br />");
            $sha1Pass = sha1($datos["password"]);
            print_r($sha1Pass);
            */
            //echo hash("sha1", $datos["password"]);
            
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbmodadmin'),"Usuario","nickname","password",'SHA1(?)');
            $authAdapter->setIdentity($datos["usuario"])->setCredential($datos["password"]);
            $auth = Zend_Auth::getInstance();
            $resultado = $auth->authenticate($authAdapter);
            //print_r($resultado->getMessages());
             
            
            if ($resultado->isValid()) {
                $data = $authAdapter->getResultRowObject(null,'password');
                $subscripcion = $loginDAO->getSubscripcion($organizacion["idOrganizacion"]);
                //print_r($subscripcion);
                $adapter = $subscripcion["adapter"];
                //unset($subscripcion["adapter"]);
                // Creamos la conexion a la bd en la que vamos a operar
                $currentDbConnection = array();
                //$currentDbConnection["adapter"] = $subscripcion["adapter"];
                $currentDbConnection["host"] = $subscripcion["host"];
                $currentDbConnection["username"] = $subscripcion["username"];
                $currentDbConnection["password"] = $subscripcion["password"];
                $currentDbConnection["dbname"] = $subscripcion["dbname"];
                $currentDbConnection["charset"] = $subscripcion["charset"];
                //$adapter = new Zend_Db_Adapter_Abstract($currentDbConnection);
                print_r("<br /><br />");
                print_r($currentDbConnection);
                print_r("<br /><br />");
                //Zend_Registry::set('dbmodencuesta', $adapter);
                //print_r(Zend_Registry::get('dbmodencuesta'));
                $db = Zend_Db::factory(strtoupper($adapter),$currentDbConnection);
                //Zend_Registry::set('dbmodencuesta', $db);
                //$dbAdapter = Zend_Registry::get('dbmodencuesta');
                print_r("<br /><br />");
                print_r($db);
                //print_r($data);
                $userInfo = array();
                $userInfo["user"] = $data;
                $userInfo["rol"] = $this->loginDAO->getRolbyId($data->idRol);
                $userInfo["organizacion"] = $organizacion;
                $userInfo["adapter"] = $db;
                //$userInfo["organizacion"] = $this->loginDAO->getOrganizacionByClave($datos["claveOrganizacion"]);
                $auth->getStorage()->write($userInfo);
                $this->_helper->redirector->gotoSimple("index", "dashboard", "encuesta");
            }else{
                $this->view->loginErrorMessages = $resultado->getMessages();
            }
            /*$namespace = $this->service->getNamespace($datos);
            $usuario = $this->loginDAO->getUsuario($datos);
            $userInfo = array();
            $userInfo["user"] = $usuario;
            $userInfo["rol"] = $this->loginDAO->getRolUsuario($usuario);
            $userInfo["organizacion"] = $this->loginDAO->getOrganizacionByClave($datos["claveOrganizacion"]); 
            //$userInfo["namespace"] = $namespace;
            //print_r($this->service->getNamespace($datos));
            
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbmodencuesta'),"Usuario","nickname","password");
            $authAdapter->setIdentity($datos["usuario"])->setCredential($datos["password"]);
            $auth = Zend_Auth::getInstance();
            //$auth->setStorage(new Zend_Auth_Storage_Session($namespace));
            $resultado = $auth->authenticate($authAdapter);
            
            if($resultado->isValid()){
                $data = $authAdapter->getResultRowObject(null,'password');
                $auth->getStorage()->write($userInfo);
                $this->view->loginResultMessages = $resultado->getMessages();
                //Zend_Registry::set("userInfo", $userInfo);
                
                $this->_helper->redirector->gotoSimple("index", "dashboard", "encuesta");
            }else{
                $this->view->loginErrorMessages = $resultado->getMessages();
            }
            */
		}
		
        
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		
		$this->_helper->redirector->gotoSimple("index", "index", "encuesta");
    }

    public function evalsAction()
    {
        // action body
        $idConjunto = $this->getParam("conjunto");
        $idEvaluacion = $this->getParam("evaluacion");
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        $encuesta = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        $evaluadores = $this->evaluacionDAO->getEvaluadoresByIdConjunto($idConjunto);
        
        $this->view->conjunto = $conjunto;
        $this->view->evaluacion = $encuesta;
        $this->view->evaluadores = $evaluadores;
    }

    public function conjuntosAction()
    {
        // action body
        $idGrupoEscolar = $this->getParam("idGrupo");
        $grupo = $this->grupoDAO->obtenerGrupo($idGrupoEscolar);
        $conjuntos = $this->evaluacionDAO->getConjuntosByIdGrupoEscolar($idGrupoEscolar);
        $this->view->grupo = $grupo;
        $this->view->conjuntos = $conjuntos;
    }

    public function asignsAction()
    {
        // action body
        $idConjunto = $this->getParam("conjunto");
        $idEvaluacion = $this->getParam("evaluacion");
        $idEvaluador = $this->getParam("evaluador");
        
        $encuesta = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        $evaluador = $this->evaluacionDAO->getEvaluadorById($idEvaluador);
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        
        $this->view->conjunto = $conjunto;
        $this->view->evaluador = $evaluador;
        $this->view->encuesta = $encuesta;
        
        //$asignacionesGrupo =  $this->asignacionDAO->obtenerAsignacionesGrupo($conjunto["idGrupoEscolar"]);
        $asignacionesConjunto = $this->evaluacionDAO->getAsignacionesByIdConjuntoAndIdEvaluacion($idConjunto, $idEvaluacion);
        // disponibles = grupo - conjunto i.e. todas - asignadas.
        
        //print_r($asignacionesConjunto);
        //$asignacionesDisponibles = array();
        //print_r($asignacionesGrupo);
        /*
        foreach ($asignacionesGrupo as $asignacionG) {
            //print_r($asignacionG);
            foreach ($asignacionesConjunto as $asignacionC) {
                if($asignacionC["idAsignacionGrupo"] == $asignacionG["idAsignacionGrupo"]){
                    //print_r("Relacionada<br />");
                    
                }else{
                    //print_r("No Relacionada<br />");
                    $asignacionesDisponibles[] = $asignacionG;
                }
            }
        }*/
        // 
        $asignacionesC = array();
        //$asignacionesD = array();
        
        foreach ($asignacionesConjunto as $asignacion) {
            $obj = array();
            $obj["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
            $obj["docente"] = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
            $asignacionesC[$asignacion["idAsignacionGrupo"]] = $obj;
        }
        /*
        foreach ($asignacionesDisponibles as $asignacion) {
            $obj = array();
            $obj["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
            $obj["docente"] = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
            $asignacionesD[$asignacion["idAsignacionGrupo"]] = $obj;
        }*/
        
        //$this->view->asignacionesDisponibles = $asignacionesD;
        $this->view->asignacionesConjunto = $asignacionesC;
    }

    public function evaluarAction()
    {
        // action body
        $request = $this->getRequest();
        $idConjunto = $this->getParam("conjunto");
        $idEvaluador = $this->getParam("evaluador");
        $idEncuesta = $this->getParam("evaluacion");
        $idAsignacion = $this->getParam("asignacion");
        
		$asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
		$evaluador = $this->evaluacionDAO->getEvaluadorById($idEvaluador);
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
		$evaluacion = $this->encuestaDAO->getEncuestaById($idEncuesta);
        
        $grupo = $this->grupoDAO->obtenerGrupo($asignacion["idGrupoEscolar"]);
        $materia = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
        $docente = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"]);
        
        $grado = $this->gradoDAO->getGradoById($grupo->getIdGrado());
        $nivel = $this->nivelDAO->obtenerNivel($grado->getIdNivelEducativo());
        
        $this->view->grupo = $grupo;
        $this->view->materia = $materia;
        $this->view->docente = $docente;
        
        $this->view->grado = $grado;
        $this->view->nivel = $nivel;
		
		$this->view->asignacion = $asignacion;
		$this->view->evaluador = $evaluador;
		$this->view->conjunto = $conjunto;
		$this->view->evaluacion = $evaluacion;
        
        //print_r($asignacion);
        //print_r($grupo->getIdGrado());
        
        $generador = $this->generador;
        $form = new Encuesta_Form_TestForm;
        $this->view->form = $form;
        
        $formulario = $generador->generarFormulario($idEncuesta, $idAsignacion);
        
        if($request->isGet()){
            $this->view->formulario = $formulario;
        }
        
        if ($request->isPost()) {
            $post = $request->getPost();
            print_r($post);
            
            try{
                //$generador->procesarFormulario($idEncuesta,$idAsignacion,$post);
                $this->view->messageSuccess = "Encuesta registrada correctamente";
            }catch(Exception $ex){
                $this->view->messageFail = "Error al Registrar la encuesta: " . $ex->getMessage();
            }
            /**/
        }
    }

    public function evaluadoresAction()
    {
        // action body
        $grupo = $this->getParam("grupo");
		$objGrupo = $this->grupoDAO->obtenerGrupo($grupo);
		$conjuntos = $this->evaluacionDAO->getEvaluadoresGrupo($grupo);
		$this->view->conjuntos = $conjuntos;
		$this->view->grupo = $objGrupo;
    }

    public function evaluacionesAction()
    {
        // action body
        $conjunto = $this->getParam("conjunto");
        $evaluador = $this->getParam("evaluador");
        
        $evaluaciones = $this->evaluacionDAO->getEvaluacionesByIdConjunto($conjunto);
        
        $objConjunto = $this->evaluacionDAO->getConjuntoById($conjunto);
        $objEvaluador = $this->evaluacionDAO->getEvaluadorById($evaluador);
        
        $this->view->evaluaciones = $evaluaciones;
        $this->view->conjunto = $objConjunto;
        $this->view->evaluador = $objEvaluador;
    }


}





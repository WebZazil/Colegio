<?php

class Soporte_JsonController extends Zend_Controller_Action
{
    
    private $equipoDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $dbAdapter = $identity["adapter"];
        
        $this->equipoDAO = new Soporte_DAO_Equipo($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        
    }

    public function consultaeqAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        $parametros = $this->getAllParams();
        unset($parametros["module"]);
        unset($parametros["controller"]);
        unset($parametros["action"]);
        
        $cadenaCondicional = "";
        
        foreach ($parametros as $key => $value) {
            if(strlen($cadenaCondicional) > 0) $cadenaCondicional .= " AND ";
            if($value != ""){
                $cadenaCondicional .=  $key . " like " . "'"."%" . $value . "%"."'";
            }
        }
        
        $querySelect = "Select * from Equipo where" . $cadenaCondicional;
        
        $db = $identity['adapter'];
        $equipos = $db->query($querySelect)->fetchAll();
        
        echo Zend_Json::encode($equipos);
    }
    
    public function queryresAction() {
        //$tipo = $this->getParam("tp");
        $ubicacion = $this->getParam("ub");
        $usuario = $this->getParam("us");
        
        $rowsEquipos = $this->equipoDAO->getEquiposByParams($ubicacion,$usuario);
        //print_r("Something");
        //print_r($rowsEquipos);
        
        echo Zend_Json::encode($rowsEquipos);
    }
    
    public function usrubsAction() {
        $idUsuario = $this->getParam("us");
        $ubicaciones = $this->equipoDAO->getUbicacionesByIdUsuario($idUsuario);
        // Obtenidas las ubicaciones en la tabla Ubicacion normalizada traemos estas claves
        $arrayUbicaciones = array();
        
        foreach ($ubicaciones as $index){
            $arrayUbicaciones[] = $index["ubicacion"];
        }
        
        $ubicacionesNorm = $this->equipoDAO->getUbicacionesByValues($arrayUbicaciones);
        echo Zend_Json::encode($ubicacionesNorm);
    }
    
    public function asigneAction() {
        $idEquipo = $this->getParam("eq");
        $idUsuario = $this->getParam("us");
        $idUbicacion = $this->getParam("ub");
        $idTipo = $this->getParam("tp");
        
        $this->equipoDAO->asignarEquipo($idEquipo, $idUsuario, $idUbicacion, $idTipo);
        
        echo Zend_Json::encode("Operacion completada exitosamente!!");
    }

}




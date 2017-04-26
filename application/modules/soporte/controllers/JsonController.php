<?php

class Soporte_JsonController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
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


}




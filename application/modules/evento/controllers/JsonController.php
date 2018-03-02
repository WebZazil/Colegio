<?php

class Evento_JsonController extends Zend_Controller_Action
{
    private $registroDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
            
        }
        
        $this->registroDAO = new Evento_Data_DAO_Evento($identity['adapter']);
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        // action body
    }

    public function findregAction()
    {
        // action body
        $evento = $this->getParam("ev");
        $nombres = $this->getParam("ns");
        $apaterno = $this->getParam("ap");
        $amaterno = $this->getParam("am");
        
        $params = array();
        if(!is_null($nombres)) $params["nombres"] = $nombres;
        if(!is_null($apaterno)) $params["apaterno"] = $apaterno;
        if(!is_null($amaterno)) $params["amaterno"] = $amaterno;
        
        $asistentes = $this->registroDAO->getAsistentesEventoByParams($evento,$params);
        echo Zend_Json::encode($asistentes);
    }


}




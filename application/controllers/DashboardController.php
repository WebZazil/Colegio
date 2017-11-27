<?php

class DashboardController extends Zend_Controller_Action
{
    private $usuarioService;

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            ;
        }
        
    }

    public function indexAction()
    {
        // action body
    }


}


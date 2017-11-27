<?php

class Soporte_DashboardController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
            $this->_helper->redirector->gotoSimple("index", "index", "soporte");
        }
    }

    public function indexAction()
    {
        // action body
    }


}


<?php

class Biblioteca_UusuarioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('dashbuser');
    }

    public function indexAction()
    {
        // action body
        $this->view->breadcrumbData = array(
            'controllerName'=>'Usuario',
            'actionName' => 'Info Usuario'
        );
    }

    public function configuracionAction()
    {
        // action body
        $this->view->breadcrumbData = array(
            'controllerName'=>'Usuario',
            'actionName' => 'Admin Usuario'
        );
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        $this->_helper->redirector->gotoSimple("index", "index", "biblioteca");
    }


}






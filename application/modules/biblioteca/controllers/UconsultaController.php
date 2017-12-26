<?php

class Biblioteca_UconsultaController extends Zend_Controller_Action
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
            'controllerName'=>'Consulta',
            'actionName' => 'Consulta Recursos'
        );
    }


}


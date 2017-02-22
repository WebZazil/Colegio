<?php

class Encuesta_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeEncuesta');
    }

    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
		
		if($request->isPost()){
			
		}
    }


}


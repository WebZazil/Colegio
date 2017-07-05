<?php

class Biblioteca_JsonController extends Zend_Controller_Action
{
	
	private $autorDAO;

    public function init()
    {
        /* Initialize action controller here */
        $this->autorDAO = new Biblioteca_Data_DAO_Autor;
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        // action body
    }

    public function autoresiAction()
    {
        // action body
        $autores = $this->autorDAO->getAutoresIndividuales();
		
		echo Zend_Json::encode($autores);
    }

    public function autoresvAction()
    {
        // action body
        $autores = $this->autorDAO->getAutoresVarios();
		
		echo Zend_Json::encode($autores);
    }


}






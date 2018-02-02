<?php

class Biblioteca_MultaController extends Zend_Controller_Action
{
    private $multaDAO;
    private $usuarioDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            ;
        }
        
        $identity = $auth->getIdentity();
        $this->multaDAO = new Biblioteca_Data_DAO_Multa($identity['adapter']);
        $this->usuarioDAO = new Biblioteca_Data_DAO_Usuario($identity['adapter']);
        
        
    }

    public function indexAction()
    {
        // action body
    }

    public function pagoAction()
    {
        // action body
        $request = $this->getRequest();
        
        $idUsuario = $this->getParam('us');
        $idMulta = $this->getParam('mu');
        
        $multa = $this->multaDAO->getRowMulta($idMulta);
        
        if ($request->isPost()) {
            $datos = $request->getPost();
            //print_r($datos);
            $multaDAO = $this->multaDAO;
            try {
                $multaDAO->pagarMulta($idMulta);
                $this->view->messageSuccess = 'La multa se ha pagado exitosamente';
            } catch (Exception $e) {
                $this->view->messageFail = 'Ha ocurrido un error: <br />'.$e->getMessage();
            }
            
        }
        
    }


}




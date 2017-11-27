<?php

class SubscripcionController extends Zend_Controller_Action
{

    private $systemDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (is_null($auth)) {
            ;
        }
        $identity = $auth->getIdentity();
        
        $this->systemDAO = new App_Data_DAO_System($identity['adapter']);
        
        
    }

    public function indexAction()
    {
        // action body
        $identity = Zend_Auth::getInstance()->getIdentity();
        $org = $identity['organizacion'];
        
        //print_r($identity);
        
        $sDAO = $this->systemDAO;
        
        $subscripciones = $sDAO->getSubscripcionesOrganizacion($org['idOrganizacion']);
        $this->view->subscripciones = $subscripciones;
        //$this->view->subscripciones = array();
    }

    public function acodifAction()
    {
        // action body
        $idSubscripcion = $this->getParam('sub');
        $sDAO = $this->systemDAO;
        $request = $this->getRequest();
        
        $subscripcion = $sDAO->getSubscripcionById($idSubscripcion);
        
        $this->view->subscripcion = $subscripcion;
        
        if($request->isPost()){
            $datos = $request->getPost();
            // print_r($datos);
            // print_r("<br /><br />");
            // $pass = $datos['password'];
            // print_r(md5($pass));
            $newPass = '';
            $enctype = '';
            
            switch ($datos['enctype']) {
                case 'NONE' : 
                    break;
                case 'SHA1' :
                    $newPass = sha1($datos['password']);
                    print_r('Codificacion sha1, size:'.strlen($newPass).'<br /><br />');
                    $enctype = 'SHA1';
                    break;
                case 'MD5' :
                    $newPass = md5($datos['password']);
                    print_r('Codificacion md5, size:'.strlen($newPass).'<br /><br />');
                    $enctype = 'MD5';
                    break;
            }
            
            if ($enctype != 'NONE') {
                
                
                $data = array(
                    'password' => $newPass,
                    'enctype' => $enctype,
                );
                
                $sDAO->editSubscription($data, $idSubscripcion);
            }
            
            
        }
        
    }


}




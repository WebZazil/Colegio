<?php

class Soporte_RestoreController extends Zend_Controller_Action
{

    private $soporteDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
        }else{
            // Continue execution
            $dbAdapter = $identity["adapter"];
            $this->soporteDAO = new Soporte_DAO_Equipo($dbAdapter);
        }
        
        
        //$this->soporteDAO = new Soporte_DAO_Equipo($dbAdapter);
    }

    public function indexAction()
    {
        // action body
    }

    public function rbiAction()
    {
        // action body
        // @TODO Checar las tablas en la base, si estan vacias proceder al restore, si no no hacer nada
        $this->soporteDAO->migrateInfo();
        /*
        $info = $this->soporteDAO->checkInfo();
        if ($info["usuarios"] && $info["ubicaciones"]) {
            print_r("Tablas vacias, restaurando contenido!"); print_r("<br />");
            
        }else{
            print_r("Tablas con contenido, restauracion abortada"); print_r("<br />");
        }
        */
        //$this->soporteDAO->migrateInfo();
        
    }

    public function asignAction()
    {
        // action body
        $usuarios = $this->soporteDAO->getUsuariosEquipos();
        $tiposEquipo = $this->soporteDAO->getTiposEquipo();
        
        $this->view->usuarios = $usuarios;
        $this->view->tiposEquipos = $tiposEquipo;
        
    }


}






<?php

class Soporte_ConsultaController extends Zend_Controller_Action
{
    private $equipoDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
        }else{
            // Continue execution
            $dbAdapter = $identity["adapter"];
            $this->equipoDAO = new Soporte_DAO_Equipo($dbAdapter);
        }
    }

    public function indexAction()
    {
        // action body
        $tipos = $this->equipoDAO->getTiposEquipo();
        $ubicaciones = $this->equipoDAO->getUbicacionesEquipos();
        $usuarios = $this->equipoDAO->getUsuariosEquipos();
        
        $this->view->ubicaciones = $ubicaciones;
        $this->view->usuarios = $usuarios;
        $this->view->tipos = $tipos;
    }


}


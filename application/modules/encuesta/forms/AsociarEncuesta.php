<?php

class Encuesta_Form_AsociarEncuesta extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        
        $encuestaDAO = new Encuesta_DAO_Encuesta($dataIdentity["adapter"]);
        //$encuestas = $encuestaDAO->obtenerEncuestas();
        $encuestas = $encuestaDAO->getAllEncuestas();
        
        $eAsignacion = new Zend_Form_Element_Select("idAsignacionGrupo");
        $eAsignacion->setAttrib("class", "form-control");
        $eAsignacion->setLabel("Docente-Materia: ");
        
        $eEncuesta = new Zend_Form_Element_Select("idEncuesta");
        $eEncuesta->setLabel("Encuestas Disponibles: ");
        $eEncuesta->setAttrib("class", "form-control");
        
        foreach ($encuestas as $encuesta) {
            $eEncuesta->addMultiOption($encuesta->getIdEncuesta(),$encuesta->getNombre());
        }
        
        $eSubmit = new Zend_Form_Element_Submit("submit");
        $eSubmit->setLabel("Asociar Encuesta con Docente-Materia");
        $eSubmit->setAttrib("class", "btn btn-success");
        
        $this->addElements(array($eAsignacion,$eEncuesta,$eSubmit));
    }


}


<?php

class Encuesta_ResultadoController extends Zend_Controller_Action
{
	private $encuestaDAO;
	private $preguntaDAO;
	private $opcionDAO;
	private $respuestaDAO;
	private $tablaPreferenciaSimple;

    public function init()
    {
        /* Initialize action controller here */
        $this->respuestaDAO = new Encuesta_DAO_Respuesta;
		$this->encuestaDAO = new Encuesta_DAO_Encuesta;
		$this->preguntaDAO = new Encuesta_DAO_Pregunta;
		$this->opcionDAO = new Encuesta_DAO_Opcion;
		$this->tablaPreferenciaSimple = new Encuesta_Model_DbTable_PreferenciaSimple;
    }

    public function indexAction()
    {
        // action body
        $this->view->encuestas = $this->encuestaDAO->obtenerEncuestas();
    }

    public function graficaAction()
    {
        // action body
        $idEncuesta = $this->getParam("idEncuesta");
		$encuesta = $this->encuestaDAO->obtenerEncuesta($idEncuesta);
		$opcionDAO = $this->opcionDAO;
		$this->view->encuesta = $encuesta;
		//Condicion para normalizar o no las preguntas: que todos los usuarios ya hayan contestado encuesta o encuesta este finalizada
		Encuesta_Util_Normalize::normalizePreguntasSS($idEncuesta);
		//Ya esta todo listo para trabajar graficas
		//Obtenemos preguntas de la encuesta
		$preguntas = $this->encuestaDAO->obtenerPreguntas($idEncuesta);
		$this->view->preguntas = $preguntas;
		
		$jsonObjects = array();
		
		foreach ($preguntas as $pregunta) {
			//Traemos las preferencias de la pregunta
			$tablaPreferenciaSimple = $this->tablaPreferenciaSimple;
			$select = $tablaPreferenciaSimple->select()->from($tablaPreferenciaSimple)->where("idPregunta = ?",$pregunta->getIdPregunta());
			$rowsPreferencias = $tablaPreferenciaSimple->fetchAll($select);
			
			$preferencias = array();
			//Iteramos en las preferencias
			foreach ($rowsPreferencias as $row) {
				$preferencia = array();
				
				$preferencia["value"] = $row->preferencia;
				$preferencia["color"] = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); //sprintf('#%06X', mt_rand(0, 0xFFFFFF)) //substr(md5(time()), 0, 6)
				$preferencia["highlight"] = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); //sprintf('#%06X', mt_rand(0, 0xFFFFFF))
				$preferencia["label"] = $opcionDAO->obtenerOpcion($row->idOpcion)->getOpcion();
				
				//$preferencias[$row->idOpcion] = json_encode($preferencias);
				$preferencias[] = $preferencia;
			}
			
			$jsonObject = json_encode($preferencias);
			//print_r("<br />");
			//print_r($jsonObject);
			$jsonObjects[$pregunta->getIdPregunta()] = $jsonObject;
			//print_r("<br />");
			//print_r($jsonObjects);
			print_r("<br />");
			print_r("<br />");
			//$preferencias[$pregunta->getIdPregunta()] = array();
		}
		
		$this->view->jsonobjs = $jsonObjects;
    }


}




<?php

/**
 * 
 */
class Encuesta_Util_Normalize {
	
	private $tablaPreferenciaSimple;
	private $tablePregunta;
	private $tableRespuesta;
	
	function __construct($argument) {
		$this->tablaPreferenciaSimple = new Encuesta_Model_DbTable_PreferenciaSimple;
		$this->tablePregunta = new Encuesta_Model_DbTable_Pregunta;
		$this->tableRespuesta = new Encuesta_Model_DbTable_Respuesta;
	}
	
	public static function nomalizePreguntasSeccion($idSeccion) {
		
	}
	
	public static function normalizePreguntasSS($idEncuesta) {
		$tablaPregunta = new Encuesta_Model_DbTable_Pregunta;
		$tablaRespuesta = new Encuesta_Model_DbTable_Respuesta;
		$tablaPreferenciaSimple = new Encuesta_Model_DbTable_PreferenciaSimple;
		
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("tipo = ?", "SS");
		//Tenemos todas las preguntas
		$rowsPreguntas = $tablaPregunta->fetchAll($select);
		//recorremos todas las preguntas
		foreach ($rowsPreguntas as $rowP) {
			//Obtenemos los id's de las opciones de la pregunta
			$idOpciones = explode(",", $rowP->opciones);
			//print_r("idOpciones Pregunta: ");
			//print_r($rowP->pregunta);
			//print_r("<br />");
			//print_r($idOpciones);	//idOpciones ordenadas de 0 a N 
			//print_r("<br />");
			//Obtenemos todas las respuestas de la pregunta
			$select = $tablaRespuesta->select()->from($tablaRespuesta)->where("idPregunta = ?", $rowP->idPregunta);
			$rowsRP = $tablaRespuesta->fetchAll($select);
			
			$preferencia = array();
			
			foreach ($idOpciones as $idOpcion) {
				$preferencia[$idOpcion] = 0;
			}
			
			print_r("<br />");
			foreach ($rowsRP as $rowRP) {
				$idOpcion = $rowRP->respuesta;
				foreach ($preferencia as $id => $numero) {
					
					if($id == $idOpcion){
						$preferencia[$id]++;
					}
				}
			}
			
			//$data = array();
			//$data["idOpcion"] = $val;
			//$data[""] = $val2;
			// ============================================================================
			foreach ($preferencia as $idOpcion => $numero) {
				//print_r($numero);
				//print_r("<br />");
				$select = $tablaPreferenciaSimple->select()->from($tablaPreferenciaSimple)->where("idPregunta = ?", $rowP->idPregunta)->where("idOpcion = ?", $idOpcion);
				$datos = array();
				$datos["idPregunta"] = $rowP->idPregunta;
				$datos["idOpcion"] = $idOpcion;
				$datos["preferencia"] = $numero;
				
				try{
					//Tratamos de insertar si falla tratamos de actualizar
					$tablaPreferenciaSimple->insert($datos);
				}catch(Exception $ex){
					print_r($ex->toString());
					//fallo, tratamos de actualizar
					$where = $tablaPreferenciaSimple->getAdapter()->quoteInto("idPregunta = ?", $rowP->idPregunta);
					$tablaPreferenciaSimple->delete($where);
					$tablaPreferenciaSimple->insert($datos);
					
				}
			}
			
			//preferencia
			/* Aqui ya preferencia tiene */
			/*
			try{
				$tablaPreferenciaSimple->insert($data);
			}catch(Exception $ex){
				$select = $tablaPreferenciaSimple->select()->from($tablaPreferenciaSimple)->where("idPregunta = ?", $idPregunta)->where("idOpcion = ?", $idOpcion);
				
				$tablaPreferenciaSimple->update($data, $select);
			}
			 * 
			 */
			//print_r("Preferencia");
			//print_r("<br />");
			//print_r($preferencia);
			//print_r("<br />");
			//print_r("===================================================");
			//print_r("<br />");
		}
	}
}

<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Respuesta implements Encuesta_Interfaces_IRespuesta {
	
	private $tablaCategoria;
	private $tablaOpcion;
	private $tablaRespuesta;
	private $tablaPreferenciaSimple;
	private $tablaERealizadas;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaCategoria = new Encuesta_Data_DbTable_CategoriasRespuesta(array('db'=>$dbAdapter));
		
		$this->tablaOpcion = new Encuesta_Data_DbTable_OpcionCategoria(array('db'=>$dbAdapter));
		
		$this->tablaRespuesta = new Encuesta_Data_DbTable_Respuesta(array('db'=>$dbAdapter));
		
		$this->tablaPreferenciaSimple = new Encuesta_Data_DbTable_PreferenciaSimple(array('db'=>$dbAdapter));
		
		$this->tablaERealizadas = new Encuesta_Data_DbTable_EncuestasRealizadas(array('db'=>$dbAdapter));
	}
	
	// =====================================================================================>>>   Buscar
	/** Obtiene las respuestas de todos los usuarios en la encuesta **/
	public function obtenerRespuestasEncuesta($idEncuesta){
		$tablaRespuesta = $this->tablaRespuesta;
		$select = $tablaRespuesta->select()->from($tablaRespuesta)->where("idEncuesta = ?", $idEncuesta);
		$rowsRespuestas = $tablaRespuesta->fetchAll($select);
		
		return $rowsRespuestas->toArray();
	}
	
	public function obtenerIdPreguntasEncuesta($idEncuesta){
		$tablaRespuesta = $this->tablaRespuesta;
		$select = $tablaRespuesta->select()->distinct()->from($tablaRespuesta, "idPregunta")->where("idEncuesta = ?", $idEncuesta);
		$ids = $tablaRespuesta->fetchAll($select);
		
		return $ids->toArray();
	}
	
	public function obtenerIdRegistroEncuesta($idEncuesta){
		$tablaRespuesta = $this->tablaRespuesta;
		$select = $tablaRespuesta->select()->distinct()->from($tablaRespuesta, "idRegistro")->where("idEncuesta = ?", $idEncuesta);
		$ids = $tablaRespuesta->fetchAll($select);
		
		return $ids->toArray();
	}
	/** Obtiene las respuestas de un usuario en la encuesta **/
	public function obtenerRespuestasEncuestaUsuario($idEncuesta, $idRegistro){
		$tablaRespuesta = $this->tablaRespuesta;
		$select = $tablaRespuesta->select()->from($tablaRespuesta)->where("idEncuesta = ?", $idEncuesta)->where("idRegistro = ?", $idRegistro);
		$rowsRespuestas = $tablaRespuesta->fetchAll($select);
		
		return $rowsRespuestas->toArray();
	}
	
	/**
	 * 
	 */
	public function obtenerRespuestasPreguntaAsignacion($idEncuesta, $idAsignacion,$idPregunta){
		$tablaRespuesta = $this->tablaRespuesta;
		$select = $tablaRespuesta->select()->from($tablaRespuesta)->where("idEncuesta=?",$idEncuesta)->where("idAsignacion=?",$idAsignacion)->where("idPregunta=?",$idPregunta);
		$respuestas = $tablaRespuesta->fetchAll($select);
		
		if(is_null($respuestas)) throw new Exception("Error: No hay respuestas para este conjunto de condiciones");
		
		return $respuestas->toArray();
	}
	
	// =====================================================================================>>>   Insertar
	public function crearRespuesta($idEncuesta, array $respuesta) {
		$tablaRespuesta = $this->tablaRespuesta;
		
		$idRespuesta = $tablaRespuesta->insert($respuesta);
		
		//$rowRes = $tablaRespuesta->fetchRow($select);
		//$modelRespuesta = new Encuesta_Model_Respuesta($rowRes->toArray());
		return $idRespuesta;
	}
	// =====================================================================================>>>   Actualizar
	public function editarRespuesta($idEncuesta, $idRegistro, array $respuesta){
		
	}
	// =====================================================================================>>>   Eliminar
	public function eliminarRespuesta($idRespuesta){
		
	}
	
	public function eliminarRespuestasGrupo($idEncuesta,$idGrupo,$idRegistro){
		$tablaRespuesta = $this->tablaRespuesta;
		$tablaPreferenciaS = $this->tablaPreferenciaSimple;
		$tablaERealizadas = $this->tablaERealizadas;
		
		try{
			$where = $tablaPreferenciaS->getAdapter()->quoteInto("idGrupo=?", $idGrupo);
			$tablaPreferenciaS->delete($where);
			//print_r($where);
			//print_r("<br />");
			$where = "idEncuesta=".$idEncuesta." and idGrupo=".$idGrupo." and idRegistro=".$idRegistro;
			$tablaRespuesta->delete($where);
			//print_r($where);
			//print_r("<br />");
			$where = "idEncuesta=".$idEncuesta." and idGrupo=".$idGrupo." and idRegistro=".$idRegistro;
			$tablaERealizadas->delete($where);
			//print_r($where);
			//print_r("<br />");
		}catch(Exception $ex){
			print_r($ex->getMessage());
		}
	}
}

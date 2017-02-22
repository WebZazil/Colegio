<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Pregunta implements Encuesta_Interfaces_IPregunta {
	
	private $tablaSeccionEncuesta;
	private $tablaGrupoSeccion;
	private $tablaPregunta;
	private $tablaPreferenciaSimple;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaSeccionEncuesta = new Encuesta_Model_DbTable_SeccionEncuesta(array('db'=>$dbAdapter));
		$this->tablaGrupoSeccion = new Encuesta_Model_DbTable_GrupoSeccion(array('db'=>$dbAdapter));
		$this->tablaPregunta = new Encuesta_Model_DbTable_Pregunta(array('db'=>$dbAdapter));
		
		$this->tablaPreferenciaSimple = new Encuesta_Model_DbTable_PreferenciaSimple(array('db'=>$dbAdapter));
	}
	// =====================================================================================>>>   Buscar
	/*
	public function obtenerPregunta($idPregunta) {
		$tabla = $this->tablaPregunta;
		
		$select = $tabla->select()->from($tabla)->where("idPregunta = ?", $idPregunta);
		$rowPregunta = $tabla->fetchRow($select);
		$modelPregunta = new Encuesta_Model_Pregunta($rowPregunta->toArray());
		
		return $modelPregunta;
	}
	*/
	
	/*
	public function obtenerPreguntasEncuesta($idEncuesta){
		$tabla = $this->tablaPregunta;
		$select = $tabla->select()->from($tabla)->where("idEncuesta=?",$idEncuesta)->order(array("idPregunta ASC"));
		$rowPreguntas = $tabla->fetchAll($select);
		$modelPreguntas = array();
		foreach ($rowPreguntas as $row) {
			$modelPregunta = new Encuesta_Model_Pregunta($row->toArray());
			$modelPreguntas[] = $modelPregunta;
		}
		
		return $modelPreguntas;
	}
	*/
	
	/*
	public function obtenerPreguntasAbiertasEncuesta($idEncuesta){
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("tipo=?","AB")->where("idEncuesta=?",$idEncuesta);
		$preguntas = $tablaPregunta->fetchAll($select);
		if(is_null($preguntas)) throw new Util_Exception_BussinessException("Error: No hay preguntas abiertas en esta encuesta", 1);
		
		return $preguntas->toArray();
	}
	*/
	// =====================================================================================>>>   Crear
	/*
	public function crearPregunta($idPadre, $tipoPadre, Encuesta_Model_Pregunta $pregunta) {
			
		if($tipoPadre === "S"){
			$tablaSeccion = $this->tablaSeccionEncuesta;
			$select = $tablaSeccion->select()->from($tablaSeccion)->where("idSeccionEncuesta = ?", $idPadre);
			$rowSeccion = $tablaSeccion->fetchRow($select);
			
			$rowSeccion->elementos++;
			$rowSeccion->save();
			$pregunta->setOrden($rowSeccion->elementos);
			
		}elseif($tipoPadre === "G"){
			$tablaGrupo = $this->tablaGrupoSeccion;
			$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoSeccion = ?", $idPadre);
			$rowGrupo = $tablaGrupo->fetchRow($select);
			
			$rowGrupo->elementos++;
			$pregunta->setOpciones($rowGrupo->opciones);
			$pregunta->setOrden($rowGrupo->elementos);
			
			$rowGrupo->save();
		}
		
		//$pregunta->setHash($pregunta->getHash());
		$pregunta->setFecha(date("Y-m-d H:i:s", time()));
		
		$tablaPregunta = $this->tablaPregunta;
		$idPregunta = $tablaPregunta->insert($pregunta->toArray());
		
		$pregunta = $this->obtenerPregunta($idPregunta);
		return $pregunta;
	}
	 * 
	 */
	 
	// =====================================================================================>>>   Editar
	/*
	public function editarPregunta($idPregunta, array $pregunta) {
		$tablaPregunta = $this->tablaPregunta;
		$where = $tablaPregunta->getAdapter()->quoteInto("idPregunta = ?", $idPregunta);
		
		$tablaPregunta->update($pregunta, $where);
	}
	*/
	// =====================================================================================>>>   Eliminar
	/*
	public function eliminarPregunta($idPregunta) {
		//Primero eliminamos de la tabla preferencia simple
		$tablaPreferenciaSimple = $this->tablaPreferenciaSimple;
		$where = $tablaPreferenciaSimple->getAdapter()->quoteInto("idPregunta = ?", $idPregunta);
		try{
			$tablaPreferenciaSimple->delete($where);
		}catch(Exception $ex){
			
		}
		
		$tablaPregunta = $this->tablaPregunta;
		$where = $tablaPregunta->getAdapter()->quoteInto("idPregunta = ?", $idPregunta);
		
		$tablaPregunta->delete($where);
	}
	*/
	// **************************************************************************************** IMPLEMENTANDO ESTANDAR DE NOMBRES
	
	/**
	 * function getPreguntaById($id) - obtiene un model pregunta por un id proporcionado
	 * @param $id - idPregunta
	 * @return $model - model de pregunta
	 */
	public function getPreguntaById($id){
		$tabla = $this->tablaPregunta;
		
		$select = $tabla->select()->from($tabla)->where("idPregunta = ?", $id);
		$rowPregunta = $tabla->fetchRow($select);
		$modelPregunta = new Encuesta_Models_Pregunta($rowPregunta->toArray());
		
		return $modelPregunta;
	}
	
	/**
	 * function getPreguntasByIdEncuesta($idEncuesta) - Obtiene las preguntas de un $idEncuesta especificado
	 * @param $idEncuesta - El $id de la encuesta
	 * @return array - conjunto de models de la pregunta
	 */
	public function getPreguntasByIdEncuesta($idEncuesta){
		$tabla = $this->tablaPregunta;
		$select = $tabla->select()->from($tabla)->where("idEncuesta=?",$idEncuesta)->order(array("idPregunta ASC"));
		$rowPreguntas = $tabla->fetchAll($select);
		
		$modelPreguntas = array();
		
		foreach ($rowPreguntas as $row) {
			$modelPregunta = new Encuesta_Models_Pregunta($row->toArray());
			$modelPreguntas[] = $modelPregunta;
		}
		
		return $modelPreguntas;
	}
	
	/**
	 * 
	 */
	public function getPreguntasAbiertasByIdEncuesta($idEncuesta){
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("tipo=?","AB")->where("idEncuesta=?",$idEncuesta);
		$preguntas = $tablaPregunta->fetchAll($select);
		if(is_null($preguntas)) throw new Util_Exception_BussinessException("Error: No hay preguntas abiertas en esta encuesta", 1);
		
		return $preguntas->toArray();
	}
	
	/**
	 * function addPregunta(...) - Agrega una pregunta especificando diversos parametros
	 * @param $idPadre - id del contenedor principal donde se agrega la pregunta
	 * @param $tipoPadre - tipo del contenedor principal, seccion o grupo
	 * @param $pregunta - la pregunta a agregar
	 */
	public function addPregunta($idPadre, $tipoPadre, Encuesta_Models_Pregunta $pregunta){
		if($tipoPadre === "S"){
			$tablaSeccion = $this->tablaSeccionEncuesta;
			$select = $tablaSeccion->select()->from($tablaSeccion)->where("idSeccionEncuesta = ?", $idPadre);
			$rowSeccion = $tablaSeccion->fetchRow($select);
			
			$rowSeccion->elementos++;
			$rowSeccion->save();
			$pregunta->setOrden($rowSeccion->elementos);
			
		}elseif($tipoPadre === "G"){
			$tablaGrupo = $this->tablaGrupoSeccion;
			$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoSeccion = ?", $idPadre);
			$rowGrupo = $tablaGrupo->fetchRow($select);
			
			$rowGrupo->elementos++;
			$pregunta->setOpciones($rowGrupo->opciones);
			$pregunta->setOrden($rowGrupo->elementos);
			
			$rowGrupo->save();
		}
		
		$pregunta->setFecha(date("Y-m-d H:i:s", time()));
		
		$tablaPregunta = $this->tablaPregunta;
		$datos = $pregunta->toArray();
		unset($datos["fecha"]);
		$idPregunta = $tablaPregunta->insert($datos);
		
		$pregunta = $this->getPreguntaById($idPregunta);//obtenerPregunta($idPregunta);
		return $pregunta;
	}
	
	/**
	 * function editPregunta($idPregunta, array $pregunta) - 
	 * @param $idPregunta - id de la pregunta a modificar
	 * @param $pregunta - array de datos a modificar de la pregunta
	 */
	public function editPregunta($idPregunta, array $pregunta){
		$tablaPregunta = $this->tablaPregunta;
		$where = $tablaPregunta->getAdapter()->quoteInto("idPregunta = ?", $idPregunta);
		
		$tablaPregunta->update($pregunta, $where);
	}
	
	/**
	 * function deletePregunta($idPregunta) - 
	 * @param $idPregunta - id de la pregunta a consultar.
	 */
	public function deletePregunta($idPregunta){
		//Primero eliminamos de la tabla preferencia simple
		$tablaPreferenciaSimple = $this->tablaPreferenciaSimple;
		$where = $tablaPreferenciaSimple->getAdapter()->quoteInto("idPregunta = ?", $idPregunta);
		try{
			$tablaPreferenciaSimple->delete($where);
		}catch(Exception $ex){
			
		}
		
		$tablaPregunta = $this->tablaPregunta;
		$where = $tablaPregunta->getAdapter()->quoteInto("idPregunta = ?", $idPregunta);
		
		$tablaPregunta->delete($where);
	}
	
}
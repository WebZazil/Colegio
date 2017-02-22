<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Seccion implements Encuesta_Interfaces_ISeccion {
	
	private $tablaEncuesta;
	private $tablaSeccionEncuesta;
	private $tablaGrupoSeccion;
	private $tablaPregunta;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaEncuesta = new Encuesta_Model_DbTable_Encuesta(array('db'=>$dbAdapter));
		$this->tablaSeccionEncuesta = new Encuesta_Model_DbTable_SeccionEncuesta(array('db'=>$dbAdapter));
		$this->tablaGrupoSeccion = new Encuesta_Model_DbTable_GrupoSeccion(array('db'=>$dbAdapter));
		$this->tablaPregunta = new Encuesta_Model_DbTable_Pregunta(array('db'=>$dbAdapter));
	}
	// =====================================================================================>>>   Buscar
	/*
	public function obtenerSeccion($idSeccion) {
		$tablaSeccionEncuesta = $this->tablaSeccionEncuesta;
		
		$select = $tablaSeccionEncuesta->select()->from($tablaSeccionEncuesta)->where("idSeccionEncuesta = ?", $idSeccion);
		$rowSeccion = $tablaSeccionEncuesta->fetchRow($select);
		
		$modelSeccion = new Encuesta_Model_Seccion($rowSeccion->toArray());
		
		return $modelSeccion;
	}
	*/
	
	/*
	public function obtenerSecciones($idEncuesta) {
		$tablaSeccionEncuesta = $this->tablaSeccionEncuesta;
		$select = $tablaSeccionEncuesta->select()->from($tablaSeccionEncuesta)->where("idEncuesta = ?", $idEncuesta);
		
		$rowsSecciones = $tablaSeccionEncuesta->fetchAll($select);
		$modelSecciones = array();
		
		foreach ($rowsSecciones as $row) {
			$modelSeccion = new Encuesta_Model_Seccion($row->toArray());
			$modelSecciones[] = $modelSeccion;
		}
		
		return $modelSecciones;
	}
	*/
	/*
	public function obtenerPreguntas($idSeccion){
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "S")->where("idOrigen = ?", $idSeccion);
		$rowsPreguntas = $tablaPregunta->fetchAll($select);
		$modelPreguntas = array();
		
		foreach ($rowsPreguntas as $row) {
			$modelPregunta = new Encuesta_Model_Pregunta($row->toArray());
			$modelPreguntas[] = $modelPregunta;
		}
		
		return $modelPreguntas;
	}
	
	public function obtenerGrupos($idSeccion){
		$tablaGrupo = $this->tablaGrupoSeccion;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idSeccionEncuesta = ?", $idSeccion);
		$rowsGrupos = $tablaGrupo->fetchAll($select);
		$modelGrupos = array();
		
		foreach ($rowsGrupos as $row) {
			$modelGrupo = new Encuesta_Models_Grupo($row->toArray());
			$modelGrupos[] = $modelGrupo;
		}
		
		return $modelGrupos;
	}
	*/
	// =====================================================================================>>>   Crear
	/*
	public function crearSeccion(Encuesta_Models_Seccion $seccion) {
		$tablaSeccion = $this->tablaSeccionEncuesta;
		$select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta = ?", $seccion->getIdEncuesta());
		$orden = count($tablaSeccion->fetchAll($select));
		$orden++;
		
		$seccion->setOrden($orden);
		$seccion->setElementos("0");
		//$seccion->setFecha(date("Y-m-d H:i:s", time()));
		
		$tablaSeccion->insert($seccion->toArray());
	}
	 * 
	 */
	// =====================================================================================>>>   Editar
	/*
	public function editarSeccion($idSeccion, array $seccion) {
		$tablaSeccion = $this->tablaSeccionEncuesta;
		$where = $tablaSeccion->getAdapter()->quoteInto("idSeccionEncuesta = ?", $idSeccion);
		
		$tablaSeccion->update($seccion, $where);
	}
	 * 
	 */
	// =====================================================================================>>>   Eliminar
	/*
	public function eliminarSeccion($idSeccion) {
		$tablaSeccion = $this->tablaSeccionEncuesta;
		$where = $tablaSeccion->getAdapter()->quoteInto("idSeccionEncuesta = ?", $idSeccion);
		
		$tablaSeccion->delete($where);
	}
	*/
	/*
	public function eliminarPreguntas($idSeccion){
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "S")->where("idOrigen = ?", $idSeccion);
		
		$tablaSeccion->delete($select);
	}
	*/
	public function eliminarGrupos($idSeccion){
		$tablaGrupoSeccion = $this->tablaGrupoSeccion;
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaGrupoSeccion->select()->from($tablaGrupoSeccion)->where("idSeccionEncuesta = ?", $idSeccion);
		$grupos = $tablaGrupoSeccion->fetchAll($select);
		//Borramos todas las preguntas grupo por grupo
		foreach ($grupos as $grupo) {
			$selectP = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "G")->where("idOrigen = ?", $grupo->idGrupoSeccion);
			$tablaPregunta->delete($selectP); 
		}
		//Borramos todos los grupos
		$tablaGrupo->delete($select);
	}
	
	// **************************************************************************************** IMPLEMENTANDO ESTANDAR DE NOMBRES
	/**
	 * getSeccionesByIdEncuesta($idEncuesta) - Obtiene array de models Seccion relacionados con la encuesta
	 * 
	 */
	public function getSeccionesByIdEncuesta($idEncuesta){
		// Instanciamos de forma local una referencia a la TablaSeccion 
		$tablaSeccionEncuesta = $this->tablaSeccionEncuesta;
		// Creamos nuestro select 
		$select = $tablaSeccionEncuesta->select()->from($tablaSeccionEncuesta)->where("idEncuesta = ?", $idEncuesta);
		// Obtenemos las secciones relacionadas con la encuesta
		$rowsSecciones = $tablaSeccionEncuesta->fetchAll($select);
		// Creamos nuestro contenedor de Models 
		$modelSecciones = array();
		
		// Si nuestro conjunto de registros no es nulo enviamos rellenamos el contenedor, si es nulo se va vacío
		if(!is_null($rowsSecciones)){
			foreach ($rowsSecciones as $row) {
				$modelSeccion = new Encuesta_Models_Seccion($row->toArray());
				$modelSecciones[] = $modelSeccion;
			}
		}
		
		return $modelSecciones;
	}
	
	/**
	 * getSeccionById($id) - Obtenemos una seccion por el $id especificado
	 * @param $id - El Id de la seccion.
	 * @return $modelSeccion | null
	 */
	public function getSeccionById($id){
		// Instanciamos de forma local una referencia a la TablaSeccion
		$tablaSeccionEncuesta = $this->tablaSeccionEncuesta;
		// Preparamos el select para
		$select = $tablaSeccionEncuesta->select()->from($tablaSeccionEncuesta)->where("idSeccionEncuesta = ?", $id);
		// Ejecutamos la consulta obteniendo nuestro registro
		$rowSeccion = $tablaSeccionEncuesta->fetchRow($select);
		// Si el registro es nulo enviamos nulo, si no enviamos model del registro
		if(is_null($rowSeccion)){
			return null;
		}else{
			$modelSeccion = new Encuesta_Models_Seccion($rowSeccion->toArray());
			return $modelSeccion;
		}		
	}
	
	/**
	 * addSeccionToEncuesta(Encuesta_Models_Seccion $seccion) - Inserta una nueva seccion en una encuesta especificada.
	 * @param $seccion - Encuesta_Models_Seccion
	 * @throws Exception 
	 */
	public function addSeccionToEncuesta(Encuesta_Models_Seccion $seccion){
		// Instanciamos de forma local una referencia a la TablaSeccion
		$tablaSeccion = $this->tablaSeccionEncuesta;
		// Seleccionamos todas las secciones de la encuesta
		$select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta = ?", $seccion->getIdEncuesta());
		// Obtenemos el numero de secciones que contiene la encuesta
		$orden = count($tablaSeccion->fetchAll($select));
		// Aumentamos en 1 para asignarle a la encuesta por agregar su orden
		$orden++;
		// Asignamos en el model su orden
		$seccion->setOrden($orden);
		// Siempre al crearse una seccion esta vacía es decir contiene 0 elementos
		//$seccion->setElementos("0");
		$datos = $seccion->toArray();
		unset($datos["fecha"]);
		// Insertamos en la TablaSeccion (Puede arrojar Excepcion de la base de datos)
		$tablaSeccion->insert($datos);
	}
	
	/**
	 * editSeccion($id, array $datos) - Edita una seccion en base al $id y los $datos proporcionados
	 * 
	 */
	public function editSeccion($id, array $datos) {
		// Instanciamos de forma local una referencia a la TablaSeccion
		$tablaSeccion = $this->tablaSeccionEncuesta;
		// Preparamos la condicion where de forma estandar, especificada por Zend Framework
		$where = $tablaSeccion->getAdapter()->quoteInto("idSeccionEncuesta = ?", $id);
		// Ejecutamos la actualizacion (Puede lanzar excepcion)
		$tablaSeccion->update($datos, $where);
	}
	
	// **************************************************************************************** Operaciones Extras
	
	/**
	 * 
	 */
	public function getGruposByIdSeccion($idSeccion){
		// Instanciamos de forma local una referencia a la TablaGrupo
		$tablaGrupo = $this->tablaGrupoSeccion;
		// Preparamos el select para obtener todos los grupos de la seccion
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idSeccionEncuesta = ?", $idSeccion);
		// Ejecutamos el select y obtenemos el conjunto de filas buscado
		$rowsGrupos = $tablaGrupo->fetchAll($select);
		// Creamos el array de models para la vista
		$modelGrupos = array();
		// Iteramos a traves del conjunto de resultados transformandolos uno a uno a models 
		foreach ($rowsGrupos as $row) {
			$modelGrupo = new Encuesta_Models_Grupo($row->toArray());
			$modelGrupos[] = $modelGrupo;
		}
		
		return $modelGrupos;
	}
	
	/**
	 * function getPreguntasByIdSeccion($idSeccion)
	 * @param $idSeccion - el id de la seccion 
	 * @return $model - 
	 */
	public function getPreguntasByIdSeccion($idSeccion){
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "S")->where("idOrigen = ?", $idSeccion);
		$rowsPreguntas = $tablaPregunta->fetchAll($select);
		$modelPreguntas = array();
		
		foreach ($rowsPreguntas as $row) {
			$modelPregunta = new Encuesta_Models_Pregunta($row->toArray());
			$modelPreguntas[] = $modelPregunta;
		}
		
		return $modelPreguntas;
	}
	
}

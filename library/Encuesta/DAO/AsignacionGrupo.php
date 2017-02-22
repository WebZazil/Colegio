<?php
/**
 * 
 */
class Encuesta_DAO_AsignacionGrupo implements Encuesta_Interfaces_IAsignacionGrupo {
	
	private $registroDAO;
	private $materiaDAO;
	private $gruposDAO;
	
	private $tablaAsignacionGrupo;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
		$this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
		$this->gruposDAO = new Encuesta_DAO_Grupos($dbAdapter);
		
		$this->tablaAsignacionGrupo = new Encuesta_Model_DbTable_AsignacionGrupo(array('db'=>$dbAdapter));
		
	}
	
	/**
	 * Obtenemos Model Registro (Docente), a partir del idAsignacion (idRegistro). 
	 */
	public function obtenerDocenteAsignacion($idAsignacion){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$idAsignacion);
		$asignacion = $tablaAsignacion->fetchRow($select);
		
		$docente = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"]);
		return $docente;
	}
	
	/**
	 * Obtener Model Grupo, a partir del idAsignacion (idGrupo)
	 */
	public function obtenerGrupoAsignacion($idAsignacion){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$idAsignacion);
		$asignacion = $tablaAsignacion->fetchRow($select);
		
		$grupo = $this->gruposDAO->obtenerGrupo($asignacion["idGrupoEscolar"]);
		return $grupo;
	}
	
	/**
	 * Obtener Model Materia, a partir del idAsignacion (idMateria)
	 */
	public function obtenerMateriaAsignacion($idAsignacion){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$idAsignacion);
		$asignacion = $tablaAsignacion->fetchRow($select);
		
		$materia = $this->materiaDAO->obtenerMateria($asignacion["idMateriaEscolar"]);
		return $materia;
	}
	
	/**
	 * En la tabla AsignacionGrupo obtenemos todas las asignaciones del profesor: idAsignacion
	 */
	public function obtenerAsignacionesDocente($idDocente){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idRegistro=?",$idDocente);
		
		$asignaciones = $tablaAsignacion->fetchAll($select);
		
		//if(is_null($asignaciones)) throw new Util_Exception_BussinessException("Error: No hay Asignaciones para el docente con Id: <strong>".$idDocente."</strong>", 1);
		if(is_null($asignaciones)){
		    return null;
		}else{
		    return $asignaciones->toArray();
		}
		
	}
	
	public function obtenerAsignacionesGrupo($idGrupo){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idGrupo=?",$idGrupo);
		
		$asignaciones = $tablaAsignacion->fetchAll($select);
		
		if(is_null($asignaciones)) throw new Util_Exception_BussinessException("Error: No hay Asignaciones para el grupo con Id: <strong>".$idGrupo."</strong>", 1);
		
		return $asignaciones->toArray();
		
	}
	
	public function obtenerIdMateriasDocente($idDocente){
		
	}
	
	public function obtenerIdGruposDocente($idDocente){
		
	}
	
	public function obtenerEvaluacionGeneralDocente($idDocente, $idEncuesta){
		//Obtenemos todas las asignaciones del profesor
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idRegistro=?",$idDocente);
		$asignaciones = $tablaAsignacion->fetchAll($select);
		$ids = array();
		foreach ($asignaciones as $asignacion) {
			$ids[] = $asignacion->idAsignacion;
		}
	}
	
	public function getAsignacionById($id){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$id);
		$row = $tablaAsignacion->fetchRow($select);
		
		return $row->toArray();
	}
	
}

<?php
/**
 * 
 */
class Encuesta_DAO_AsignacionGrupo implements Encuesta_Interfaces_IAsignacionGrupo {
	
	private $registroDAO;
	private $materiaDAO;
	private $gruposDAO;
	
	private $tablaAsignacionGrupo;
	private $tablaGrupoEscolar;
	private $tablaMateriaEscolar;
	private $tablaDocente;
	
	public function __construct($dbAdapter) {
		$config = array('db' => $dbAdapter);
		
		$this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
		$this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
		$this->gruposDAO = new Encuesta_DAO_Grupos($dbAdapter);
		
		$this->tablaAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
		$this->tablaGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
		$this->tablaMateriaEscolar = new Encuesta_Data_DbTable_MateriaEscolar($config);
		$this->tablaDocente = new Encuesta_Data_DbTable_Docente($config);
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
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idGrupoEscolar=?",$idGrupo);
		
		$asignaciones = $tablaAsignacion->fetchAll($select);
		
		if(is_null($asignaciones)) throw new Exception("Error: No hay Asignaciones para el grupo con Id: <strong>".$idGrupo."</strong>", 1);
		
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
	
	public function getAsignacionesByIdGrupo($idGrupo) {
	    $tG = $this->tablaGrupoEscolar;
	    $select = $tG->select()->from($tG)->where('idGrupoEscolar=?',$idGrupo);
	    $rowGrupo = $tG->fetchRow($select)->toArray();
	    
	    $idsMaterias = explode(',', $rowGrupo['idsMaterias']);
	    
	    $tAG = $this->tablaAsignacionGrupo;
	    $select = $tAG->select()->from($tAG)->where('idGrupoEscolar=?',$idGrupo)->where('idMateriaEscolar IN (?)',$idsMaterias);
	    //print_r($select->__toString());
	    $rowsAsignaciones = $tAG->fetchAll($select);
	    
	    return $rowsAsignaciones->toArray();
	}
	
	public function getObjectAsignaciones($asignaciones,$idGrupo = null){
	    $tME = $this->tablaMateriaEscolar;
	    $tD = $this->tablaDocente;
	    $tGE = $this->tablaGrupoEscolar;
	    
	    $container = array();
	    
	    foreach ($asignaciones as $asignacion) {
	        $obj = array();
	        $obj['asignacion'] = $asignacion;
	        // Materia Escolar
	        $select = $tME->select()->from($tME)->where('idMateriaEscolar=?',$asignacion['idMateriaEscolar']);
	        $rowME = $tME->fetchRow($select)->toArray();
	        $obj['materiae'] = $rowME;
	        // Grupo Escolar
	        $select = $tGE->select()->from($tGE)->where('idGrupoEscolar=?',$asignacion['idGrupoEscolar']);
	        $rowGE = $tGE->fetchRow($select)->toArray();
	        $obj['grupoe'] = $rowGE;
	        // Docente
	        $select = $tD->select()->from($tD)->where('idDocente=?',$asignacion['idDocente']);
	        $rowD = $tD->fetchRow($select)->toArray();
	        $obj['docente'] = $rowD;
	        
	        $container[] = $obj;
	    }
	    
	    return $container;
	}
    
    /**
     * Comprueba si ya hay un docente asignado en un conjunto (grupo-materia-docente) en T.AsignacionGrupo
     */
    public function existeDocenteEnAsignacion($idGrupoEscolar, $idMateriaEscolar) {
        $tablaAsignacion = $this->tablaAsignacionGrupo;
        $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idGrupoEscolar=?",$idGrupoEscolar)
            ->where("idMateriaEscolar=?",$idMateriaEscolar);
        $rowAsignacion = $tablaAsignacion->fetchRow($select);
        
        if(is_null($rowAsignacion)){
            return false;
        }else{
            return true;
        }
        //return $rowAsignacion->toArray();
    }
    
    /**
     * Retorna una asignacion para obtener el docente, proporcionamos:
     * @param idGrupoEscolar
     * @param idMateriaEscolar
     */
    public function obtenerDocenteEnAsignacion($idGrupoEscolar, $idMateriaEscolar) {
        $tablaAsignacion = $this->tablaAsignacionGrupo;
        $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idGrupoEscolar=?",$idGrupoEscolar)
            ->where("idMateriaEscolar=?",$idMateriaEscolar);
        $rowAsignacion = $tablaAsignacion->fetchRow($select);
        
        return $rowAsignacion->toArray();
    }
	
}

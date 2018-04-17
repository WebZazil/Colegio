<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Grupos implements Encuesta_Interfaces_IGrupos {
	private $dbAdapter;
	private $tablaGrupo;
	//private $tablaProfesoresGrupo;
	private $tablaCiclo;
	private $tablaAsignacionGrupo;
	private $tablaMateria;
	
	public function __construct($dbAdapter) {
		$config = array('db' => $dbAdapter);
		$this->dbAdapter = $dbAdapter;
		
		$this->tablaGrupo = new Encuesta_Data_DbTable_GrupoEscolar($config);
		$this->tablaCiclo = new Encuesta_Data_DbTable_CicloEscolar($config);
		$this->tablaAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
		$this->tablaMateria = new Encuesta_Data_DbTable_MateriaEscolar($config);
	}
	
	public function obtenerGrupos($idGrado,$idCiclo){
		$tablaGrupo = $this->tablaGrupo;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGradoEducativo = ?",$idGrado)->where("idCicloEscolar = ?",$idCiclo)->order("grupoEscolar ASC");
		$rowsGrupos = $tablaGrupo->fetchAll($select);
		
		return $rowsGrupos->toArray();
	}
	
	public function obtenerGrupo($idGrupo){
		$tablaGrupo = $this->tablaGrupo;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoEscolar = ?",$idGrupo);
		$rowGrupo = $tablaGrupo->fetchRow($select);
		
		return $rowGrupo->toArray();
	}
	
	public function obtenerAsignacion($idAsignacion){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$idAsignacion);
		$asignacion = $tablaAsignacion->fetchRow($select);
		//if(is_null($asignacion)) throw new Util_Exception_BussinessException("Error: No hay asignacion de Docente-Materia con el id:<strong>".$idAsignacion."</strong>", 1);
		if (is_null($asignacion)) {
			return null;
		}else{
		    return $asignacion->toArray();
		}
		
	}
	
	/**
	 * crearGrupo - Crea un grupo en el ciclo escolar actual
	 */
	public function crearGrupo(array $grupo){
		$tablaGrupo = $this->tablaGrupo;
		$grupo["idsMaterias"] ="";
		$tablaGrupo->insert($grupo);
		
		/*
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGradoEducativo = ?",$idGrado)->where("idCicloEscolar = ?",$idCiclo)->where("grupo = ?",$grupo->getGrupo());
		$grupoe = $tablaGrupo->fetchRow($select);
		
		$grupo->setIdCiclo($idCiclo);
		$grupo->setIdGrado($idGrado);
		$grupo->setHash($grupo->getHash());
		
		if(!is_null($grupoe))throw new Util_Exception_BussinessException("Error Grupo: <strong>".$grupo->getGrupo()."</strong> duplicado en el sistema");
		
		try{
			$tablaGrupo->insert($grupo->toArray());
		}catch(Exception $ex){
			throw new Util_Exception_BussinessException("Error al Insertar el Grupo: " . $grupo->getGrupo());			
		}
		*/
	}
	
	public function obtenerDocentes($idGrupo){
		//$tablaProfesoresGrupo = $this->tablaProfesoresGrupo;
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idGrupoEscolar = ?",$idGrupo);
		$profesores = $tablaAsignacion->fetchAll($select);
		$profesoresGrupo = array();
		
		if(!is_null($profesores)){
			$materiaDAO = new Encuesta_DAO_Materia($this->dbAdapter);
			$registroDAO = new Encuesta_DAO_Registro($this->dbAdapter);
			
			foreach ($profesores as $profesor) {
				$obj = $profesor->toArray();
				$obj["materia"] = $materiaDAO->obtenerMateria($obj["idMateriaEscolar"]); //Objeto Encuesta_Model_Materia;
				$obj["profesor"] = $registroDAO->obtenerRegistro($profesor->idRegistro); //Objeto Encuesta_Model_Profesor
				
				$profesoresGrupo[$obj["idMateriaEscolar"]] = $obj;
			}
		}
		
		
		return $profesoresGrupo;
	}
	
	public function agregarDocenteGrupo(array $registro){
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		//Si ya hay un registro de esta materia
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idGrupoEscolar=?",$registro["idGrupoEscolar"])->where("idMateriaEscolar=?",$registro["idMateriaEscolar"]);
		$row = $tablaAsignacion->fetchRow($select);
		
		if(!is_null($row)){
			throw new Exception("Error: <strong>Docente</strong> ya registrado en la <strong>Materia</strong> seleccionada");
		}else{
			try{
				$tablaAsignacion->insert($registro);
			}catch(Exception $ex){
				throw new Exception("Error: <strong>". $ex->getMessage()."</strong>");
				
			}
			
		}
	}
	/**
	 * 
	 */
	public function obtenerMaterias($idGrupo) {
		$tablaGrupo = $this->tablaGrupo;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoEscolar=?", $idGrupo);
		$rowGrupo = $tablaGrupo->fetchRow($select);
		$tablaMateria = $this->tablaMateria;
		$idsMaterias = null;
		
		if(is_null($rowGrupo) || $rowGrupo->idsMaterias == ""){
			return array();
		}else{
			$idsMaterias = explode(",", $rowGrupo->idsMaterias);
			$select = $tablaMateria->select()->from($tablaMateria)->where("idMateriaEscolar IN (?)",$idsMaterias);
			$rowsMaterias = $tablaMateria->fetchAll($select);
			return $rowsMaterias->toArray();
		}
	}
	
	/**
	 * 
	 */
	public function asociarMateriaAgrupo($idGrupoEscolar, $idMateriaEscolar) {
		$tablaGrupo = $this->tablaGrupo;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoEscolar=?",$idGrupoEscolar);
		$rowGrupo = $tablaGrupo->fetchRow($select);
		
		$idsMaterias = explode(",", $rowGrupo->idsMaterias);
		
		if (!in_array($idMateriaEscolar, $idsMaterias)) {
			$idsMaterias[] = $idMateriaEscolar;
		}
		
		$rowGrupo->idsMaterias = implode(",", $idsMaterias);
		$rowGrupo->save();
	}
	
	public function getAllGruposByIdCicloEscolar($idCicloEscolar) {
		$tablaGrupos = $this->tablaGrupo;
		$where = $tablaGrupos->getAdapter()->quoteInto("idCicloEscolar=?", $idCicloEscolar);
		
		$rowsGrupos = $tablaGrupos->fetchAll($where);
		
		return $rowsGrupos->toArray();
	}
	
}

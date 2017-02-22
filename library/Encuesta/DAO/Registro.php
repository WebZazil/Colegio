<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Registro implements Encuesta_Interfaces_IRegistro {
	
	private $tablaRegistro = null;
    private $tablaGrupoEscolar = null;
    private $tablaAsignacionGrupo = null;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaRegistro = new Encuesta_Model_DbTable_Registro(array('db'=>$dbAdapter));
        $this->tablaGrupoEscolar = new Encuesta_Model_DbTable_GrupoEscolar(array('db'=>$dbAdapter));
        $this->tablaAsignacionGrupo = new Encuesta_Model_DbTable_AsignacionGrupo(array('db'=>$dbAdapter));
	}
	
	// =====================================================================================>>>   Buscar
	public function obtenerRegistro($idRegistro){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro = ?", $idRegistro);
		$rowRegistro = $tablaRegistro->fetchRow($select);
		$modelRegistro = null;
		
		if(!is_null($rowRegistro)){
			$modelRegistro = new Encuesta_Model_Registro($rowRegistro->toArray());
		}
		
		return $modelRegistro;
	}
	
	public function obtenerRegistroReferencia($referencia){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->where("referencia = ?", $referencia);
		$rowRegistro = $tablaRegistro->fetchRow($select);
		
		$modelRegistro = new Encuesta_Model_Registro($rowRegistro->toArray());
		
		return $modelRegistro;
	}
	
	public function obtenerRegistros(){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->order("apellidos");
		$rowsRegistros = $tablaRegistro->fetchAll($select);
		$modelRegistros = array();
		foreach ($rowsRegistros as $rowRegistro) {
			$modelRegistro = new Encuesta_Model_Registro($rowRegistro->toArray());
			$modelRegistros[] = $modelRegistro;
		}
		
		return $modelRegistros;
	}
	
	public function obtenerDocentes(){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->where("tipo=?","DO")->order("apellidos");
		$rowsDocentes = $tablaRegistro->fetchAll($select);
		$modelDocentes = array();
		foreach ($rowsDocentes as $row) {
			$modelDocente = new Encuesta_Model_Registro($row->toArray());
			$modelDocentes[] = $modelDocente;
		}
		
		return $modelDocentes;
	}
	// =====================================================================================>>>   Insertar
	public function crearRegistro(array $registro){
		$tablaRegistro = $this->tablaRegistro;
		$tablaRegistro->insert($registro);
	}
	
	// =====================================================================================>>>   Actualizar
	public function editarRegistro($idRegistro, array $registro){
		$tablaRegistro = $this->tablaRegistro;
		$where = $tablaRegistro->getAdapter()->quoteInto("idRegistro=?", $idRegistro);
		
		$tablaRegistro->update($registro, $where);
	}
	// =====================================================================================>>>   Eliminar
	public function eliminarRegistro($idRegistro){
		
	}
	
    /**
     * Obtenemos todos los docentes del ciclo escolar especificado.
     */
    public function getDocentesByIdCiclo($idCicloEscolar) {
        $tablaGrupoE = $this->tablaGrupoEscolar;
        $select = $tablaGrupoE->select()->from($tablaGrupoE)->where("idCicloEscolar=?",$idCicloEscolar);
        $gruposEscolares = $tablaGrupoE->fetchAll($select);
        // Obtuvimos todos los grupos pertenecientes al ciclo escolar.
        $tablaAG = $this->tablaAsignacionGrupo;
        $idsDocentes = array(); 
        // Iteraremos a traves de todos los grupos buscando sus asignaciones
        foreach ($gruposEscolares as $grupoEscolar) {
            $select = $tablaAG->select()->from($tablaAG)->where("idGrupoEscolar=?",$grupoEscolar["idGrupoEscolar"]);
            $asignaciones = $tablaAG->fetchAll($select);
            foreach ($asignaciones as $asignacion) {
                if(! in_array($asignacion["idRegistro"], $idsDocentes)){
                    $idsDocentes[] = $asignacion["idRegistro"];
                }
            }
        }
        $tablaRegistro = $this->tablaRegistro;
        $select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro IN (?)",$idsDocentes)->order("apellidos ASC");
        $docentes = $tablaRegistro->fetchAll($select);
        //print_r($docentes);
        
        return $docentes;
    }
	
}

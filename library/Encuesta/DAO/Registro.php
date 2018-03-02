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
	    $config = array('db'=>$dbAdapter);
		
		$this->tablaRegistro = new Encuesta_Data_DbTable_Registro($config);
        $this->tablaGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tablaAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
	}
	
	// =====================================================================================>>>   Buscar
	public function obtenerRegistro($idRegistro){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro = ?", $idRegistro);
		$rowRegistro = $tablaRegistro->fetchRow($select);
		
		return $rowRegistro->toArray();
	}
	
	public function obtenerRegistroReferencia($referencia){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->where("referencia = ?", $referencia);
		$rowRegistro = $tablaRegistro->fetchRow($select);
		
		return $rowRegistro->toArray();
	}
	
	public function obtenerRegistros(){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->order("apellidos");
		$rowsRegistros = $tablaRegistro->fetchAll($select);
		
		return $rowsRegistros->toArray();
	}
	
	public function obtenerDocentes(){
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->where("tipo=?","DO")->order("apellidos");
		$rowsDocentes = $tablaRegistro->fetchAll($select);
		
		return $rowsDocentes->toArray();
	}
	// =====================================================================================>>>   Insertar
	public function crearRegistro(array $registro){
		$tablaRegistro = $this->tablaRegistro;
		$tablaRegistro->insert($registro);
	}
	
	// =====================================================================================>>>   Actualizar
	/**
     * funcion editarRegistro
     * @param $idRegistro - el Id del registro a actualizar
     * @param $registro - el registro a actualizar
     */
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

    public function getDocentesByParam($param) {
    	$tablaRegistro = $this->tablaRegistro;
        $select = $tablaRegistro->select()->from($tablaRegistro)->where("nombres LIKE ?", "%{$param}%")->orWhere("apellidos LIKE ?", "%{$param}%");
        $rowsRegistros = $tablaRegistro->fetchAll($select);
        //print_r($select->__toString());
        //print_r($rowsRegistros->toArray());
        
        return $rowsRegistros->toArray();
    }
	
}

<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Opcion implements Encuesta_Interfaces_IOpcion {
	
	private $tablaCategoria;
	private $tablaOpcion;
	private $tablaPregunta;
	private $tablaGrupo;
	private $tablaPreferenciaS;
	
	private $tablaValoresOpcion;
	
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaCategoria = new Encuesta_Model_DbTable_CategoriasRespuesta(array('db'=>$dbAdapter));
		$this->tablaOpcion = new Encuesta_Model_DbTable_OpcionCategoria(array('db'=>$dbAdapter));
		$this->tablaPregunta = new Encuesta_Model_DbTable_Pregunta(array('db'=>$dbAdapter));
		$this->tablaGrupo = new Encuesta_Model_DbTable_GrupoSeccion(array('db'=>$dbAdapter));
		$this->tablaPreferenciaS = new Encuesta_Model_DbTable_PreferenciaSimple(array('db'=>$dbAdapter));
	}
	
	// =====================================================================================>>>   Buscar
	public function obtenerOpcion($idOpcion){
		$tablaOpcion = $this->tablaOpcion;
		$select = $tablaOpcion->select()->from($tablaOpcion)->where("idOpcionCategoria = ?", $idOpcion);
		$rowOpcion = $tablaOpcion->fetchRow($select);
		
		$modelOpcion = new Encuesta_Models_Opcion($rowOpcion->toArray());
		
		return $modelOpcion;
	}
	
	/**
	 * 
	 */
	public function obtenerOpcionesCategoria($idCategoria) {
		$tablaOpcion = $this->tablaOpcion;
		$select = $tablaOpcion->select()->from($tablaOpcion)->where("idCategoriasRespuesta = ?", $idCategoria);
		$rowsOpcion = $tablaOpcion->fetchAll($tablaOpcion);
		
		$modelOpciones = array();
		
		if(!is_null($rowsCategoria)){
			foreach ($rowsCategoria as $row) {
				$modelOpcion = new Encuesta_Models_Opcion($row->toArray());
				$modelOpciones[] = $modelOpcion;
			}
		}
		
		return $modelOpciones;
	}
	
	/**
	 * 
	 */
	public function obtenerOpcionesPregunta($idPregunta){
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("idPregunta = ?", $idPregunta);
		$rowOpciones = $tablaPregunta->fetchRow($select);
		
		$modelOpciones = array();
		//Si no es nulo traemos las opciones
		if(!is_null($rowOpciones)){
			$opciones = explode(",", $rowOpciones->opciones);
			
			foreach ($opciones as $opcion) {
				$modelOpcion = $this->obtenerOpcion($opcion);
				$modelOpciones[] = $modelOpcion;
			}
		}
		
		return $modelOpciones;
	}
	
	public function obtenerOpcionesGrupo($idGrupo){
		$tablaGrupo = $this->tablaGrupo;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoSeccion = ?", $idGrupo);
		$rowGrupo = $tablaGrupo->fetchRow($select);
		$opciones = explode(",", $rowGrupo->opciones);
		
		$modelOpciones = array();
		
		foreach ($opciones as $opcion) {
			$modelOpcion = $this->obtenerOpcion($opcion);
			$modelOpciones[] = $modelOpcion;
		}
		
		return $modelOpciones;
	}
	
	public function obtenerValorOpcionMayor($idGrupo){
		$tablaGrupo = $this->tablaGrupo;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupo=?",$idGrupo);
		$rowGrupo = $tablaGrupo->fetchRow($select);
		$tablaOpcion = $this->tablaOpcion;
		$ids = explode(",", $rowGrupo->opciones);
		$select = $tablaOpcion->select()->from($tablaOpcion,array("idOpcion", "valor"=>"MAX(vreal)"))->where("idOpcion IN (?)",$ids);
		
		$row = $tablaOpcion->fetchRow($select);
		return $row->toArray();
	}
	
	public function obtenerValorOpcionMenor($idGrupo){
		
	}
	// =====================================================================================>>>   Insertar
	public function crearOpcion($idCategoria, array $opcion){
		$tablaOpcion = $this->tablaOpcion;
		$select = $tablaOpcion->select()->from($tablaOpcion)->where("idCategoriasRespuesta = ?", $idCategoria);
		$orden = count($tablaOpcion->fetchAll($select));
		$orden++;
		$opcion["orden"] = $orden;
		//$opcion->setHash($opcion->getHash());
		//$opcion->setFecha(date("Y-m-d H:i:s", time()));
		//print_r($opcion->toArray());
		$tablaOpcion->insert($opcion);
	}
	
	public function asignarValorOpcion($idOpcion, $valor){
		$tablaValorOpcion = $this->tablaValoresOpcion;
		try{
			$tablaValorOpcion->insert($valor);
		}catch(Exception $ex){
			throw new Util_Exception_BussinessException("Error: <strong>".$ex->getMessage()."</strong>");
		}
	}
	// =====================================================================================>>>   Actualizar
	public function editarOpcion($idOpcion, array $opcion){
		$tablaOpcion = $this->tablaOpcion;
		$where = $tablaOpcion->getAdapter()->quoteInto("idOpcion", $idOpcion);
		
		$tablaOpcion->update($opcion, $where);
	}
	
	// =====================================================================================>>>   Eliminar
	public function eliminarOpcion($idOpcion){
		$tablaOpcion = $this->tablaOpcion;
		$where = $tablaOpcion->getAdapter()->quoteInto("idOpcion", $idOpcion);
		
		$tablaOpcion->update($opcion->toArray(), $where);
	}
	// =====================================================================================>>>   Asociar
	public function asociarOpcionesPregunta($idPregunta, array $opciones){
		$tablaPregunta = $this->tablaPregunta;
		$where = $tablaPregunta->getAdapter()->quoteInto("idPregunta = ?", $idPregunta);
		
		$vector = implode(",", $opciones);
		
		$data = array();
		$data["opciones"] = $vector;
		
		$tablaPregunta->update($data, $where);
	}
	
	public function asociarOpcionesGrupo($idGrupo, array $opciones) {
		$tablaGrupo = $this->tablaGrupo;
		$where = $tablaGrupo->getAdapter()->quoteInto("idGrupo = ?", $idGrupo);
		$vector = implode(",", $opciones);
		
		$data = array();
		$data["opciones"] = $vector;
		
		$tablaGrupo->update($data, $where);
	}
	// =====================================================================================>>>   Normalizar
	public function normalizarOpciones(){
		$tablaPreferenciaS = $this->tablaPreferenciaS;
		$preferencias = $tablaPreferenciaS->fetchAll();
		$opcionDAO = new Encuesta_DAO_Opcion;
		foreach ($preferencias as $preferencia) {
			if(is_null($preferencia->total)){
				$opcion = $opcionDAO->obtenerOpcion($preferencia->idOpcion);
				$total = $opcion->getVreal() * $preferencia->preferencia;
				
				$tablaPreferenciaS->update(array("total"=>$total), "idPregunta=".$preferencia->idPregunta." and idGrupo=".$preferencia->idGrupo." and idOpcion=".$preferencia->idOpcion);
				
				
			}
		}
		
		
		
	}
}

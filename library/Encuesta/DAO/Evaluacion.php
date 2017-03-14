<?php
/**
 * 
 */
class Encuesta_DAO_Evaluacion implements Encuesta_Interfaces_IEvaluacion {
	
	private $tablaConjuntoEvaluador;
	private $tablaEvaluador;
	//private $tablaEvaluacionesConjunto;
    private $tablaEvaluacionConjunto;
	private $tablaAsignacionGrupo;
	private $tablaEncuesta;	
	private $tablaEvaluacionRealizada;
	
	function __construct($dbAdapter) {
		$this->tablaConjuntoEvaluador = new Encuesta_Model_DbTable_ConjuntoEvaluador(array('db'=>$dbAdapter));
		$this->tablaEvaluador = new Encuesta_Model_DbTable_Evaluador(array('db'=>$dbAdapter));
		$this->tablaEvaluacionConjunto = new Encuesta_Model_DbTable_EvaluacionConjunto(array('db'=>$dbAdapter));
		$this->tablaAsignacionGrupo = new Encuesta_Model_DbTable_AsignacionGrupo(array('db'=>$dbAdapter));
		$this->tablaEncuesta = new Encuesta_Model_DbTable_Encuesta(array('db'=>$dbAdapter));
		$this->tablaEvaluacionRealizada = new Encuesta_Model_DbTable_EvaluacionRealizada(array('db'=>$dbAdapter));
	}
	
	public function getEvaluadoresByTipo($tipo) {
		$tablaEvaluador = $this->tablaEvaluador;
		$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("tipo=?",$tipo);
		$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
		
		if (is_null($rowsEvaluadores)) {
			return null;
		} else {
			return $rowsEvaluadores->toArray();
		}
	}
	
	public function addEvaluador(array $evaluador) {
		$tablaEvaluador = $this->tablaEvaluador;
		
		$evaluador["creacion"] = date("Y-m-d H:i:s");
		$tablaEvaluador->insert($evaluador);
	}
	
	public function getEvaluadorById($idEvaluador) {
		$tablaEvaluador = $this->tablaEvaluador;
		$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador=?",$idEvaluador);
		$rowEvaluador = $tablaEvaluador->fetchRow($select);
		
		if (is_null($rowEvaluador)) {
			return null;
		}else{
			return $rowEvaluador->toArray();
		}
	}
	
	public function getEvaluadoresByNombresOApellidos($nombres, $apellidos) {
		$tablaEvaluadores = $this->tablaEvaluador;
		$db = $tablaEvaluadores->getAdapter();//->quoteInto($text, $value);
		$where = null;
		
		if ((!is_null($nombres) || $nombres != "") && (!is_null($apellidos) || $apellidos != "")) {
			$where = $tablaEvaluadores->select()->from($tablaEvaluadores)->where("nombres LIKE ?", "%{$nombres}%" )->where("nombres LIKE ?", "%{$apellidos}%" );
			//print_r("Q1");
		} elseif ((is_null($nombres) || $nombres == "") && (!is_null($apellidos) || ($apellidos != "")) ) {
			$where = $db->quoteInto("apellidos LIKE ?", "%$apellidos%");
			//print_r("Q2");
		}elseif( (is_null($apellidos) || $apellidos == "") && (!is_null($nombres) || ($nombres != "")) ){
			$where = $db->quoteInto("nombres LIKE ?","%$nombres%");	
			//print_r("Q3");
		}
		
		//print_r("<br />");
		//print_r($where);
		//print_r("<br />");
		$rowsEvaluadores = $tablaEvaluadores->fetchAll($where);
		//print_r($rowsEvaluadores->toArray());
		
		if(is_null($rowsEvaluadores)){
			return null;
		}else{
			return $rowsEvaluadores->toArray();
		}
	}
	
	public function getEvaluadoresByIdConjunto($idConjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaConjunto->fetchRow($where);
		
		if (is_null($rowConjunto)) {
			return array();
		}elseif($rowConjunto->idsEvaluadores != ""){
			$idsEvaluadores = explode(",", $rowConjunto->idsEvaluadores);
		
			$tablaEvaluador = $this->tablaEvaluador;
			$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador IN (?)", $idsEvaluadores);
			$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
			
			return $rowsEvaluadores->toArray();
			
		}else{
			return array();
		}
	}

	/**
	 * Inserta un nuevo conjunto de Evaluacion
	 */
	public function addConjuntoEvaluador($conjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$conjunto["creacion"] = date("Y-m-d H:i:s", time());
		$conjunto["idsEvaluadores"] = "";
		//print_r($conjunto);
		$tablaConjunto->insert($conjunto);
	}
	
	public function getAllConjuntos() {
		$rowsConjuntos = $this->tablaConjuntoEvaluador->fetchAll();
		
		if (is_null($rowsConjuntos)) {
			return array();
		}else{
			return $rowsConjuntos->toArray();
		}
	}
	
	public function getConjuntoById($idConjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaConjunto->fetchRow($where);
		
		if (is_null($rowConjunto)) {
			return null;
		} else {
			return $rowConjunto->toArray();
		}
	}
	
	public function getConjuntosByIdGrupoEscolar($idGrupoEscolar) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idGrupoEscolar=?", $idGrupoEscolar);
		
		$rowsConjuntos = $tablaConjunto->fetchAll($where);
		
		if(is_null($rowsConjuntos)){
			return array();
		}else{
			return $rowsConjuntos->toArray();
		}
	}
	
	public function getEvaluadoresByString($string) {
		$valor = "'%$string%'";
		$tablaEvaluador = $this->tablaEvaluador;
		$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("nombres LIKE $valor OR apellidos LIKE $valor");
		$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
		
		if(is_null($rowsEvaluadores)){
			return array();
		}else{
			return $rowsEvaluadores->toArray();
		}
	}
	
	public function asociarEvaluadorAConjunto($idEvaluador,$idConjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$where = $tablaConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaConjunto->fetchRow($where);
		
		$idsEvaluadores = explode(",", $rowConjunto->idsEvaluadores);
		
		if(in_array($idEvaluador, $idsEvaluadores)){
			// Esta en el Array, no se agrega nada
		}else{
			$idsEvaluadores[] = $idEvaluador;
		}
		
		$rowConjunto->idsEvaluadores = implode(",", $idsEvaluadores);
		$rowConjunto->save();
	}
	
    /**
     * Obtenemos las relaciones materia-docente del conjunto de evaluacion
     */
	public function getAsignacionesByIdConjunto($idConjunto) {
		$tablaEvalsConjunto = $this->tablaEvaluacionConjunto;
		$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowsConjunto = $tablaEvalsConjunto->fetchAll($where);
		
        $idsAsignaciones = array();
        foreach ($rowsConjunto as $rowConjunto) {
            $ids = explode(",", $rowConjunto->idsAsignacionesGrupo);
            if (!empty($ids)) {
                foreach ($ids as $key => $id) {
                    $idsAsignaciones[] = $id;
                }
            }
        }
        
        
		$tablaAsignacion = $this->tablaAsignacionGrupo;
		
		if(is_null($rowsConjunto)){
			return array();
		}else{
			//$idsAsignaciones = explode(",", $idsAsignaciones); 
			$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo IN (?)", $idsAsignaciones);
			$rowsAsignaciones = $tablaAsignacion->fetchAll($select);
			
			return $rowsAsignaciones->toArray();
		}
	}
    
    /**
     * 
     */
    public function getAsignacionesByIdConjuntoAndIdEvaluacion($idConjunto,$idEvaluacion) {
        $tablaEvalsConjunto = $this->tablaEvaluacionConjunto;
        $select = $tablaEvalsConjunto->select()->from($tablaEvalsConjunto)->where("idConjuntoEvaluador=?",$idConjunto)->where("idEvaluacion=?",$idEvaluacion);
        //$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
        $rowConjunto = $tablaEvalsConjunto->fetchRow($select);
        $tablaAsignacion = $this->tablaAsignacionGrupo;
        
        if(!is_null($rowConjunto)){
            $idsAsignaciones = explode(",", $rowConjunto->idsAsignacionesGrupo);
            
            $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo IN (?)", $idsAsignaciones);
            $rowsAsignaciones = $tablaAsignacion->fetchAll($select);
            
            return $rowsAsignaciones->toArray();
        }else{
            return array();
        }
    }
	
	public function asociarAsignacionAConjunto($idConjunto, $idEvaluacion, $idAsignacion) {
		$tablaEvalsConjunto = $this->tablaEvaluacionConjunto;
        $select = $tablaEvalsConjunto->select()->from($tablaEvalsConjunto)->where("idConjuntoEvaluador=?",$idConjunto)->where("idEvaluacion=?",$idEvaluacion);
		//$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
		$rowConjunto = $tablaEvalsConjunto->fetchRow($select);
		$idsAsignaciones = explode(",", $rowConjunto->idsAsignacionesGrupo);
		
		//print_r($idsAsignaciones);
		
		if (!in_array($idAsignacion, $idsAsignaciones)) {
			$idsAsignaciones[] = $idAsignacion;
			$rowConjunto->idsAsignacionesGrupo = implode(",", $idsAsignaciones);
			$rowConjunto->save();
		}
		
	}
	
	public function asociarEvaluacionAConjunto($idConjunto, $idEncuesta) {
		$tablaEvalsConjunto = $this->tablaEvaluacionConjunto;
        $select = $tablaEvalsConjunto->select()->from($tablaEvalsConjunto)->where("idConjuntoEvaluador=?",$idConjunto)->where("idEvaluacion=?",$idEncuesta);
        $rowConjunto = $tablaEvalsConjunto->fetchRow($select);
        
        if (is_null($rowConjunto)) {
            $datos = array();
            $datos["idConjuntoEvaluador"] = $idConjunto;
            $datos["idEvaluacion"] = $idEncuesta;
            $datos["idsAsignacionesGrupo"] = "";
            
            $tablaEvalsConjunto->insert($datos);
        }
	}
	
	public function getEvaluacionesByIdConjunto($idConjunto) {
		$tablaEvalsConjunto = $this->tablaEvaluacionConjunto;
		$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
        
		$rowsConjunto = $tablaEvalsConjunto->fetchAll($where);
        /*
        if (!empty($rowsConjunto)) {
            print_r("NoVacio");
            print_r($rowsConjunto->toArray());
        }
        */
        if(!empty($rowsConjunto)){
            $idsEncuestas = array();
            foreach ($rowsConjunto as $rowConjunto) {
                $idsEncuestas[] = $rowConjunto->idEvaluacion;
            }
            if (!empty($idsEncuestas)) {
                $tablaEncuesta = $this->tablaEncuesta;
                $select = $tablaEncuesta->select()->from($tablaEncuesta)->where("idEncuesta IN (?)", $idsEncuestas);
                $rowsEncuestas = $tablaEncuesta->fetchAll($select);
                
                return $rowsEncuestas->toArray();
            }else{
                return array();
            }
        }else{
            return array();
        }
	}
	
	/**
	 * 
	 */
	public function saveEncuestaEvaluador($idEvaluador, $idConjunto, $idEvaluacion, $idAsignacion, $jsonEncuesta) {
		$tablaEvRe = $this->tablaEvaluacionRealizada;
		$select = $tablaEvRe->select()->from($tablaEvRe)
					->where("idEvaluador=?",$idEvaluador)
					->where("idConjuntoEvaluador=?",$idConjunto)
					->where("idAsignacionGrupo=?",$idAsignacion)
					->where("idEvaluacion=?",$idEvaluacion);
		$rowEvaluacion = $tablaEvRe->fetchRow($select);
		
		if (is_null($rowEvaluacion)) {
			$datos = array();
			$datos["idEvaluador"] = $idEvaluador;
			$datos["idConjuntoEvaluador"] = $idConjunto;
			$datos["idAsignacionGrupo"] = $idAsignacion;
			$datos["idEvaluacion"] = $idEvaluacion;
			$datos["json"] = $jsonEncuesta;
			$datos["data"] = "";
			
			//print_r($datos);
			
			$tablaEvRe->insert($datos);
			return true;
		}else{
			//echo Zend_Json::encode("Encuesta ya realizada");
			return false;
		}
		
	}
	
	public function getEvaluadoresGrupo($idGrupo) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		$select = $tablaConjunto->select()->from($tablaConjunto)->where("idGrupoEscolar");
		$rowsConjuntos = $tablaConjunto->fetchAll($select);
		$conjuntos = array();
		$tablaEvaluador = $this->tablaEvaluador;
		
		if(!is_null($rowsConjuntos)){
			foreach ($rowsConjuntos as $rowConjunto) {
				if($rowConjunto->idsEvaluadores != ""){
					$idConjunto = $rowConjunto->idConjuntoEvaluador;
					$arrayConjunto = array();
					$ids = explode(",", $rowConjunto->idsEvaluadores);
					foreach ($ids as $key => $value) {
						//$obj = array("idConjunto"=>$idConjunto, "idEvaluador"=>$value);
						$arrayConjunto[] = $value;
					}
					$conjuntos[$idConjunto] = $arrayConjunto;
				}
			}
		}
		//print_r($conjuntos);
        $arrayConjuntos = array();
        foreach ($conjuntos as $index => $conjunto) {
            $select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador IN (?) ", $conjunto);
            $rowsEvaluadores = $tablaEvaluador->fetchAll($select);
            //print_r($rowsEvaluadores->toArray());
            if(!is_null($rowsEvaluadores)){
                $arrayConjuntos[$index] = $rowsEvaluadores->toArray();
            }
        }
        
		/*
		$tablaEvaluador = $this->tablaEvaluador;
		$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador IN (?)", $idsEvaluadores);
		$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
		
		return $rowsEvaluadores->toArray();
		*/
		return $arrayConjuntos;
	}
}

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
	    $config = array('db'=>$dbAdapter);
	    
		$this->tablaConjuntoEvaluador = new Encuesta_Data_DbTable_ConjuntoEvaluador($config);
		$this->tablaEvaluador = new Encuesta_Data_DbTable_Evaluador($config);
		$this->tablaEvaluacionConjunto = new Encuesta_Data_DbTable_EvaluacionConjunto($config);
		$this->tablaAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
		$this->tablaEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
		$this->tablaEvaluacionRealizada = new Encuesta_Data_DbTable_EvaluacionRealizada($config);
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
	
	public function addEvaluador(array $datos) {
		$tablaEvaluador = $this->tablaEvaluador;
		return $tablaEvaluador->insert($datos);
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
	 * 
	 * @param array $conjunto
	 * @return mixed|array
	 */
	public function addConjuntoEvaluador($conjunto) {
		$tablaConjunto = $this->tablaConjuntoEvaluador;
		
		return $tablaConjunto->insert($conjunto);
	}
	
	public function getAllConjuntos() {
		$rowsConjuntos = $this->tablaConjuntoEvaluador->fetchAll();
		return $rowsConjuntos->toArray();
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
		
		return $rowsConjuntos->toArray();
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
	    $tablaAsignacion = $this->tablaAsignacionGrupo;
		// ----
        $select = $tablaEvalsConjunto->select()->from($tablaEvalsConjunto)->where("idConjuntoEvaluador=?",$idConjunto);
        $rowsEvalsConjunto = $tablaEvalsConjunto->fetchAll($select);
        $arrayEvals = array();
        foreach ($rowsEvalsConjunto as $rowEval) {
            $idEncuesta = $rowEval->idEncuesta;
            $idsAsignaciones = explode(",", $rowEval->idsAsignacionesGrupo);
            $arrAsignacion = array();
            foreach ($idsAsignaciones as $index => $idAsignacion) {
                if($idAsignacion != ""){
                    $where = $tablaAsignacion->getAdapter()->quoteInto("idAsignacionGrupo=?", $idAsignacion);
                    $rowAsignacion = $tablaAsignacion->fetchRow($where);
                    $arrAsignacion[] = $rowAsignacion->toArray();
                }
            }
            $arrayEvals[$idEncuesta] = $arrAsignacion;
        }
        
        //print_r($arrayEvals);
        //$evaluacionesConjunto = $ta
        return $arrayEvals;
	}

    /**
     * Obtenemos las asignaciones del grupo proporcionado
     */
    public function getAsignacionesByIdGrupo($idGrupo) {
        $tablaAsignacion = $this->tablaAsignacionGrupo;
        $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idGrupoEscolar=?",$idGrupo);
        $rowsAsignaciones = $tablaAsignacion->fetchAll($select)->toArray();
        
        return $rowsAsignaciones;
    }
    
    /**
     * Obtenemos todas las asignaciones de todos lo conjuntos del grupo escolar sin repetir
     */
    public function getAsignacionesConjuntosByIdGrupo($idGrupo) {
        $tablaConjunto = $this->tablaConjuntoEvaluador;
        $select = $tablaConjunto->select()->from($tablaConjunto)->where("idGrupoEscolar=?",$idGrupo);
        $rowsConjuntos = $tablaConjunto->fetchAll($select);
        //print_r($rowsConjuntos->toArray()); print_r("<br /><br />---------------------------------------------<br />");
        
        //Conjuntos Obtenidos
        $idsConjuntos = array();
        foreach ($rowsConjuntos as $rowConjunto) {
            if (!in_array($rowConjunto->idConjuntoEvaluador, $idsConjuntos)) {
                $idsConjuntos[] = $rowConjunto->idConjuntoEvaluador;
            }
        }
        // Ids Conjuntos del Grupo Obtenidos
        $tablaEvalCo = $this->tablaEvaluacionConjunto;
        $select = $tablaEvalCo->select()->from($tablaEvalCo)->where("idConjuntoEvaluador IN (?)", $idsConjuntos);
        $rowsEvaluaciones = $tablaEvalCo->fetchAll($select);
        //evaluaciones de conjuntos obtenidas
        //print_r($rowsEvaluaciones->toArray());
        $idsAsignacionesGrupo = array();
        foreach ($rowsEvaluaciones as $rowEvaluacion) {
            $idsAsignaciones = explode(",", $rowEvaluacion->idsAsignacionesGrupo);
            foreach ($idsAsignaciones as $key => $idAsignacionGrupo) {
                if (!in_array($idAsignacionGrupo, $idsAsignacionesGrupo)) {
                    if ($idAsignacionGrupo != "") {
                        $idsAsignacionesGrupo[] = $idAsignacionGrupo;
                    }
                }
            }
        }
        
        //print_r("IdsAsignacionGrupo sin repetir del grupo: ".$idGrupo);
        //print_r($idsAsignacionesGrupo);
        
        return $idsAsignacionesGrupo;
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
        $select = $tablaEvalsConjunto->select()->from($tablaEvalsConjunto)->where("idConjuntoEvaluador=?",$idConjunto)->where("idEncuesta=?",$idEvaluacion);
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
        $select = $tablaEvalsConjunto->select()->from($tablaEvalsConjunto)->where("idConjuntoEvaluador=?",$idConjunto)->where("idEncuesta=?",$idEncuesta);
        $rowConjunto = $tablaEvalsConjunto->fetchRow($select);
        
        if (is_null($rowConjunto)) {
            $datos = array();
            $datos['idEncuesta'] = $idEncuesta;
            $datos['idConjuntoEvaluador'] = $idConjunto;
            $datos['idsAsignacionesGrupo'] = "";
            $datos['creacion'] = date('Y-m-d H:i:s');
            
            return $tablaEvalsConjunto->insert($datos);
        }
	}
	
	public function getEvaluacionesByIdConjunto($idConjunto) {
		$tablaEvalsConjunto = $this->tablaEvaluacionConjunto;
		$where = $tablaEvalsConjunto->getAdapter()->quoteInto("idConjuntoEvaluador=?", $idConjunto);
        
		$rowsConjunto = $tablaEvalsConjunto->fetchAll($where)->toArray();
        
        if(!empty($rowsConjunto)){
            $idsEncuestas = array();
            foreach ($rowsConjunto as $rowConjunto) {
                $idsEncuestas[] = $rowConjunto['idEncuesta'];
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

    public function getEvaluacionesByAsignacionAndEvaluacion($idAsignacion, $idEvaluacion) {
        $tablaEvalReal = $this->tablaEvaluacionRealizada;
        $select = $tablaEvalReal->select()->from($tablaEvalReal)->where("idAsignacionGrupo=?",$idAsignacion)->where("idEvaluacion=?",$idEvaluacion);
        $rowsEvalsReal = $tablaEvalReal->fetchAll($select);
        
        return $rowsEvalsReal->toArray();
    }
	
	/**
	 * 
	 */
	public function saveEncuestaEvaluador($idEvaluador, $idConjunto, $idEvaluacion, $idAsignacion, $jsonEncuesta) {
		$tablaEvRe = $this->tablaEvaluacionRealizada;
		$select = $tablaEvRe->select()->from($tablaEvRe)
					->where("idEvaluador=?",$idEvaluador)
					->where("idConjuntoEvaluador=?",$idConjunto)
					->where("idEncuesta=?",$idEvaluacion)
					->where("idAsignacionGrupo=?",$idAsignacion);
		
		$rowEvaluacion = $tablaEvRe->fetchRow($select);
		
		if (is_null($rowEvaluacion)) {
			$datos = array();
			$datos["idEvaluador"] = $idEvaluador;
			$datos["idConjuntoEvaluador"] = $idConjunto;
			$datos["idEncuesta"] = $idEvaluacion;
			$datos["idAsignacionGrupo"] = $idAsignacion;
			$datos["json"] = $jsonEncuesta;
			$datos["creacion"] = date('Y-m-d H:i:s');
			
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
        $select = $tablaConjunto->select()->from($tablaConjunto)->where("idGrupoEscolar=?",$idGrupo);
        $rowsConjuntos = $tablaConjunto->fetchAll($select);
        $ids = array();
        $conjuntos = array();
        foreach ($rowsConjuntos as $rowConjunto) {
            //conjunto $rowConjunto->idConjuntoEvaluador
            $idConjunto = $rowConjunto->idConjuntoEvaluador;
            $arrayConjunto = array();
            $idsEvaluadores = explode(",", $rowConjunto->idsEvaluadores);
            foreach ($idsEvaluadores as $key => $id) {
                if ($id != "") {
                    $arrayConjunto[] = $id;
                }
            }
            $conjuntos[$idConjunto] = $arrayConjunto;
        }
        //print_r("Conjuntos y IdsEvaluadores:");
        //print_r($conjuntos);
        //print_r("<br />");
        $tablaEvaluador = $this->tablaEvaluador;
        foreach ($conjuntos as $idConjunto => $conjunto) {
            //print_r($conjunto);
            //print_r("<br />");
            foreach ($conjunto as $key => $idEvaluador) {
                $select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador=?",$idEvaluador);
                $rowEvaluador = $tablaEvaluador->fetchRow($select);
                $conjuntos[$idConjunto][$key] = $rowEvaluador->toArray();
            }
            
        }
        //print_r("<br />");
        //print_r($conjuntos);
        /*
        foreach ($conjuntos as $idConjunto => $conjunto) {
            //print_r($conjunto);
            print_r("<br />");
            foreach ($conjunto as $key => $value) {
                print_r($value);
                print_r("<br />");
            }
        }
        */
        //$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador IN (?)",$ids);
        //$rowsEvaluadores = $tablaEvaluador->fetchAll($select);
        
        //return $rowsEvaluadores->toArray();
        return $conjuntos;
	}

    public function IsAsignacionConjuntoEvaluada($idConjunto,$idEvaluador,$idEvaluacion,$idAsignacion) {
        $tablaEvaluacionR = $this->tablaEvaluacionRealizada;
        $select = $tablaEvaluacionR->select()
            ->from($tablaEvaluacionR)
            ->where("idConjuntoEvaluador=?",$idConjunto)
            ->where("idEvaluador=?",$idEvaluador)
            ->where("idEvaluacion=?",$idEvaluacion)
            ->where("idAsignacionGrupo=?",$idAsignacion);
        $rowEvaluada = $tablaEvaluacionR->fetchRow($select);
        if (is_null($rowEvaluada)) {
            return false;
        }else{
            return true;
        }
    }
    
    public function getAllResultadosConjunto($idConjunto) {
        
        $tablaEvalRel = $this->tablaEvaluacionRealizada;
        $select = $tablaEvalRel->select()->from($tablaEvalRel)->where("idConjuntoEvaluador=?",$idConjunto);
        $rowsEvalsReal = $tablaEvalRel->fetchAll($select);
        
        //print_r($rowsEvalsReal);
        /*
        foreach ($rowsEvalsReal as $rowEval) {
            print_r($rowEval); print_r("<br />");
        }
        */
        //$rowsResultados =
        if (!is_null($rowsEvalsReal)) {
            return $rowsEvalsReal->toArray();
        }else{
            return array();
        }
    }
    
    public function getResultadoEvaluacionAsignacionByIdConjunto($idConjunto,$idEvaluacion, $idAsignacion) {
        $tablaEvalRel = $this->tablaEvaluacionRealizada;
        $select = $tablaEvalRel->select()->from($tablaEvalRel)
            ->where("idConjuntoEvaluador=?",$idConjunto)
            ->where("idEvaluacion=?",$idEvaluacion)
            ->where("idAsignacionGrupo=?",$idAsignacion);
            
        //print_r($select->__toString()); print_r("<br /><br />");
        $rowsEvalsRel = $tablaEvalRel->fetchAll($select);
        
        //print_r($rowsEvalsRel->toArray());
        if (!is_null($rowsEvalsRel)) {
            return $rowsEvalsRel->toArray();
        } else {
            return array();
        }
    }
    
    /**
     * Obtiene los tipos de evaluacion que tiene relacionada la idAsignacion
     * Es decir: obtiene idEvaluacion 3,2,4, etc si estan relacionados a esta asignacion
     */
    public function getTiposEvaluacionByIdAsignacion($idAsignacion) {
        $tablaEvalRel = $this->tablaEvaluacionRealizada;
        $select = $tablaEvalRel->select()->distinct()->from($tablaEvalRel, array('idEvaluacion'))->where("idAsignacionGrupo=?",$idAsignacion);
        $rowsEvalsReal = $tablaEvalRel->fetchAll($select);
        
        //print_r($rowsEvalsReal->toArray());
        return $rowsEvalsReal->toArray();
    }
    
    /**
     * Cuando estamos pro
     */
    public function getResultadoIndividual($idAsignacion, $idEvaluacion, $idEvaluador) {
        $tablaEvalRel = $this->tablaEvaluacionRealizada;
        $select = $tablaEvalRel->select()->from($tablaEvalRel)->where("idAsignacionGrupo=?", $idAsignacion)
            ->where("idEvaluacion=?", $idEvaluacion)
            ->where("idEvaluador=?", $idEvaluador);
        
        $rowEvaluacion = $tablaEvalRel->fetchRow($select);
        
        return $rowEvaluacion->toArray();
    }
    
    /**
     * Edita un evaluador
     */
    public function editaEvaluador($idEvaluador, $datos) {
        $tablaEvaluador = $this->tablaEvaluador;
        //$select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador=?",$idEvaluador);
        //$rowEvaluador = $tablaEvaluador->fetchRow($select);
        $where = $tablaEvaluador->getAdapter()->quoteInto("idEvaluador=?", $idEvaluador);
        
        $tablaEvaluador->update($datos, $where);
    }
    
    /**
     * Normaliza los nombres de los evaluadores
     */
    public function normalizarEvaluadores() {
        $tablaEvaluador = $this->tablaEvaluador;
        $rowsEvaluadores = $tablaEvaluador->fetchAll();
        
        foreach ($rowsEvaluadores as $key => $rowEvaluador) {
            //print_r($rowEvaluador->nombres." ". $rowEvaluador->apellidos);
            $rowEvaluador->nombres = ucwords(strtolower($rowEvaluador->nombres));
            $rowEvaluador->apellidos = ucwords(strtolower($rowEvaluador->apellidos));
            $rowEvaluador->save();
        }
        
    }
}

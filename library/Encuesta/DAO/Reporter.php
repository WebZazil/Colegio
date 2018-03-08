<?php
/**
 * DAO especializado en la construccion de reportes
 */
class Encuesta_DAO_Reporter {
    
    private $tableEncuesta = null;
    private $tableSeccion = null;
    private $tableGrupo = null;
    private $tablePregunta = null;
    
    private $tableAsignacion = null;
    private $tableGrupoEscolar = null;
    private $tableMateriaEscolar = null;
    private $tableDocente = null;
    private $tableEncuestasRealizadas = null;
    private $tablePreferenciaSimple = null;
    private $tableOpcionCategoria = null;
    private $tableGradoEducativo = null;
    private $tableNivelEducativo = null;
	
	function __construct($dbAdapter) {
	    $config = array('db' => $dbAdapter);
		$this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableSeccion = new Encuesta_Data_DbTable_SeccionEncuesta($config);
        $this->tableGrupo = new Encuesta_Data_DbTable_GrupoSeccion($config);
        $this->tablePregunta = new Encuesta_Data_DbTable_Pregunta($config);
        
        $this->tableAsignacion = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        $this->tableGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tableMateriaEscolar = new Encuesta_Data_DbTable_MateriaEscolar($config);
        $this->tableDocente = new Encuesta_Data_DbTable_Registro($config);
        $this->tableEncuestasRealizadas = new Encuesta_Data_DbTable_EncuestasRealizadas($config);
        
        $this->tablePreferenciaSimple = new Encuesta_Data_DbTable_PreferenciaSimple($config);
        $this->tableOpcionCategoria = new Encuesta_Data_DbTable_OpcionCategoria($config);
        
        $this->tableGradoEducativo = new Encuesta_Data_DbTable_GradoEducativo($config);
        $this->tableNivelEducativo = new Encuesta_Data_DbTable_NivelEducativo($config);
        
	}
    
    /**
     * 
     */
    public function getEncuestaById($idEncuesta) {
        $tablaEncuesta = $this->tableEncuesta;
        $where = $tablaEncuesta->getAdapter()->quoteInto('idEncuesta=?', $idEncuesta);
        $rowEncuesta = $tablaEncuesta->fetchRow($where);
        
        if(!is_null($rowEncuesta)) {
            return $rowEncuesta->toArray();
        }else{
            return null;
        }
    }
    
    /**
     * 
     */
    public function getSeccionesByIdEncuesta($idEncuesta) {
        $tablaSeccion = $this->tableSeccion;
        $where = $tablaSeccion->getAdapter()->quoteInto('idEncuesta=?', $idEncuesta);
        $rowsSecciones = $tablaSeccion->fetchAll($where);
        
        if (!is_null($rowsSecciones)) {
            return $rowsSecciones->toArray();
        } else {
            return null;
        }
        
    }
    
    /**
     * 
     */
    public function getGruposByIdSeccion($idSeccion) {
        $tablaGrupo = $this->tableGrupo;
        $where = $tablaGrupo->getAdapter()->quoteInto('idSeccionEncuesta=?', $idSeccion);
        $rowsGrupo = $tablaGrupo->fetchAll($where);
        
        if (!is_null($rowsGrupo)) {
            return $rowsGrupo->toArray();
        } else {
            return null;
        }
        
    }
    
    /**
     * 
     */
    public function getAsignacionGrupoById($idAsignacion) {
        $tablaAsignacionG = $this->tableAsignacion;
        $where = $tablaAsignacionG->getAdapter()->quoteInto('idAsignacionGrupo=?', $idAsignacion);
        $rowAsignacion = $tablaAsignacionG->fetchRow($where);
        
        if(!is_null($rowAsignacion)){
            return $rowAsignacion->toArray();
        }else{
            return null;
        }
        
    }
    
    /**
     * Obtiene grupo escolar dado un Id de grupo
     */
    public function getGrupoEscolarById($idGrupoEscolar) {
        $tablaGrupoE = $this->tableGrupoEscolar;
        $where = $tablaGrupoE->getAdapter()->quoteInto('idGrupoEscolar=?', $idGrupoEscolar);
        $rowGrupoE = $tablaGrupoE->fetchRow($where);
        
        if (!is_null($rowGrupoE)) {
            return $rowGrupoE->toArray();
        } else {
            return null;
        }
        
        
    }
    
    /**
     * 
     */
    public function getMateriaEscolarById($idMateriaEscolar) {
        $tablaMateriaE = $this->tableMateriaEscolar;
        $where = $tablaMateriaE->getAdapter()->quoteInto('idMateriaEscolar=?', $idMateriaEscolar);
        $rowMateriaE = $tablaMateriaE->fetchRow($where);
        
        if (!is_null($rowMateriaE)) {
            return $rowMateriaE->toArray();
        } else {
            return null;
        }
    }
    
    /**
     * 
     */
    public function getDocenteById($idDocente) {
        $tablaDocente = $this->tableDocente;
        $where = $tablaDocente->getAdapter()->quoteInto('idRegistro=?', $idDocente);
        $rowDocente = $tablaDocente->fetchRow($where);
        
        if (!is_null($rowDocente)) {
            return $rowDocente->toArray();
        } else {
            return null;
        }
        
    }
    
    public function getEncuestasRealizadas($idEncuesta, $idAsignacion) {
        $tablaEncuestasR = $this->tableEncuestasRealizadas;
        $select = $tablaEncuestasR->select()->from($tablaEncuestasR)->where("idEncuesta=?",$idEncuesta)->where("idAsignacionGrupo=?",$idAsignacion);
        $rowAsignacion = $tablaEncuestasR->fetchRow($select);
        
        if (!is_null($rowAsignacion)) {
            return $rowAsignacion->toArray();
        } else {
            return null;
        }
        
    }
    
    public function getPeguntasGrupoSeccion($idGrupoSeccion) {
        $tablaPregunta = $this->tablePregunta;
        $select = $tablaPregunta->select()->from($tablaPregunta)->where("origen=?","G")->where("idOrigen=?",$idGrupoSeccion);
        $rowsPreguntas = $tablaPregunta->fetchAll($select);
        
        
    }
    
    /**
     * Normaliza las preferencias que no concuerden correspondientes a una asignacion dada
     */
    public function normalizePreferenciaAsignacion($idAsignacion) {
        $tablaPreferenciaS = $this->tablePreferenciaSimple;
        $where = $tablaPreferenciaS->getAdapter()->quoteInto("idAsignacionGrupo=?", $idAsignacion);
        $rowsPS = $tablaPreferenciaS->fetchAll($where);
        $tablaOpcionCat = $this->tableOpcionCategoria;
        
        foreach ($rowsPS as $rowPS) {
            $where = $tablaOpcionCat->getAdapter()->quoteInto("idOpcionCategoria=?", $rowPS->idOpcionCategoria);
            $rowOpcion = $tablaOpcionCat->fetchRow($where);
            
            $preferenciaTotal = $rowOpcion->valorEntero * $rowPS->preferencia;
            
            if($preferenciaTotal != $rowPS->total){
                $rowPS->total = $preferenciaTotal;
                $rowPS->save();
            }
            //$rowOpcion =
             
        }
        
        
    }
    
    /**
     * 
     */
    public function getGradoEducativoById($idGradoEducativo) {
        $tablaGradoEducativo = $this->tableGradoEducativo;
        $select = $tablaGradoEducativo->select()->from($tablaGradoEducativo)->where("idGradoEducativo=?",$idGradoEducativo);
        $rowGradoEducativo = $tablaGradoEducativo->fetchRow($select);
        
        if (is_null($rowGradoEducativo)) {
            return null;
        } else {
            return $rowGradoEducativo->toArray();
        }
    }
    
    /**
     * 
     */
    public function getNivelEducativoById($idNivelEducativo) {
        $tablaNivel = $this->tableNivelEducativo;
        $select = $tablaNivel->select()->from($tablaNivel)->where("idNivelEducativo=?",$idNivelEducativo);
        $rowNivel = $tablaNivel->fetchRow($select);
        
        if (is_null($rowNivel)) {
            return null;
        } else {
            return $rowNivel->toArray();
        }
        
    }
    
}

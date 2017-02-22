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
		$this->tableEncuesta = new Encuesta_Model_DbTable_Encuesta(array('db'=>$dbAdapter));
        $this->tableSeccion = new Encuesta_Model_DbTable_SeccionEncuesta(array('db'=>$dbAdapter));
        $this->tableGrupo = new Encuesta_Model_DbTable_GrupoSeccion(array('db'=>$dbAdapter));
        $this->tablePregunta = new Encuesta_Model_DbTable_Pregunta(array('db'=>$dbAdapter));
        
        $this->tableAsignacion = new Encuesta_Model_DbTable_AsignacionGrupo(array('db'=>$dbAdapter));
        $this->tableGrupoEscolar = new Encuesta_Model_DbTable_GrupoEscolar(array('db'=>$dbAdapter));
        $this->tableMateriaEscolar = new Encuesta_Model_DbTable_MateriaEscolar(array('db'=>$dbAdapter));
        $this->tableDocente = new Encuesta_Model_DbTable_Registro(array('db'=>$dbAdapter));
        $this->tableEncuestasRealizadas = new Encuesta_Model_DbTable_EncuestasRealizadas(array('db'=>$dbAdapter));
        
        $this->tablePreferenciaSimple = new Encuesta_Model_DbTable_PreferenciaSimple(array('db'=>$dbAdapter));
        $this->tableOpcionCategoria = new Encuesta_Model_DbTable_OpcionCategoria(array('db'=>$dbAdapter));
        
        $this->tableGradoEducativo = new Encuesta_Model_DbTable_GradoEducativo(array('db'=>$dbAdapter));
        $this->tableNivelEducativo = new Encuesta_Model_DbTable_NivelEducativo(array('db'=>$dbAdapter));
        
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

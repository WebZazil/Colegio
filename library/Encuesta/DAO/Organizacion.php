<?php
/**
 * DAO de administracion de los objetos por Organizacion dada de alta en el modulo de encuesta.
 */
class Encuesta_DAO_Organizacion {
    
    private $tableOrganizacion;
    private $tablaEncuestasOrganizacion;
    private $tablaNivelesEducativosOrganizacion;
    private $tablaRegistroOrganizacion;
    private $tablaPlanesOrganizacion;
    private $tablaCategoriasRespOrganizacion;
	
	function __construct() {
		$dbAdapter = Zend_Registry::get('dbmodencuesta');
        
        $this->tablaEncuestasOrganizacion = new Encuesta_Model_DbTable_EncuestasOrganizacion(array('db'=>$dbAdapter));
        $this->tablaNivelesEducativosOrganizacion = new Encuesta_Model_DbTable_NivelesEducativosOrganizacion(array('db'=>$dbAdapter));
        $this->tablaRegistroOrganizacion = new Encuesta_Model_DbTable_RegistroOrganizacion(array('db'=>$dbAdapter));
        $this->tablaPlanesOrganizacion = new Encuesta_Model_DbTable_PlanesOrganizacion(array('db'=>$dbAdapter));
        $this->tablaCategoriasRespOrganizacion = new Encuesta_Model_DbTable_CategoriasRespOrganizacion(array('db'=>$dbAdapter));
	}
    
    public function addEncuestaToOrganizacion($idOrganizacion, $idEncuesta){
        $tablaEnOrg = $this->tablaEncuestasOrganizacion;
        $select = $tablaEnOrg->select()->from($tablaEnOrg)->where("idOrganizacion=?",$idOrganizacion);
        $rowEnOrg = $tablaEnOrg->fetchRow($select);
        // Si ya existe la organizacion
        if(!is_null($rowEnOrg)){
            $strIdsEncuestas = $rowEnOrg->idsEncuesta;
            $arrIdsEncuestas = explode(",", $strIdsEncuestas);
            // Si la clave no esta, la agregamos al conjunto de claves
            if (!in_array($idEncuesta, $arrIdsEncuestas)) {
                $arrIdsEncuestas[] = $idEncuesta;
                $strIdsEncuestas = implode(",", $arrIdsEncuestas);
                $datos = array("idsEncuesta"=>$strIdsEncuestas);
                $where = $tablaEnOrg->getAdapter()->quoteInto("idOrganizacion", $idOrganizacion);
                $tablaEnOrg->update($datos, $where);
            }
        }
        
    }
    
    public function addNivelEducativoToOrganizacion($idOrganizacion, $idNivelEducativo){
        $tablaNivEdOrga = $this->tablaNivelesEducativosOrganizacion;
        $select = $tablaNivEdOrga->select()->from($tablaNivEdOrga)->where("idOrganizacion=?",$idOrganizacion);
        $rowNivelOrg = $tablaNivEdOrga->fetchRow($select);
        if(!is_null($rowNivelOrg)){
            $strIdsNiveles = $rowNivelOrg->idsNiveles;
            $arrIdsNiveles = explode(",", $strIdsNiveles);
            if (!in_array($idNivelEducativo, $arrIdsNiveles)) {
                $arrIdsNiveles[] = $idNivelEducativo;
                $strIdsNiveles = implode(",", $arrIdsNiveles);
                $datos = array("idsNiveles"=>$strIdsNiveles);
                $where = $tablaNivEdOrga->getAdapter()->quoteInto("idOrganizacion=?", $idOrganizacion);
                $tablaNivEdOrga->update($datos, $where);
            }
        }
    }
    
    public function addRegistroToOrganizacion($idOrganizacion, $idRegistro){
        $tablaRegOrg = $this->tablaRegistroOrganizacion;
        $select = $tablaRegOrg->select()->from($tablaRegOrg)->where("idOrganizacion=?",$idOrganizacion);
        $rowRegOrg = $tablaRegOrg->fetchRow($select);
        if(!is_null($rowRegOrg)){
            $strIdsRegistros = $rowRegOrg->idsRegistro;
            $arrIdsRegistros = explode(",", $strIdsRegistros);
            if(!in_array($idRegistro, $arrIdsRegistros)){
                $arrIdsRegistros[] = $idRegistro;
                $strIdsRegistros = implode(",", $arrIdsRegistros);
                $datos = array("idsRegistro"=>$idRegistro);
                $where = $tablaRegOrg->getAdapter()->quoteInto("idsRegistro", $strIdsRegistros);
                $tablaRegOrg->update($datos, $where);
            }
        }
    }
    
    public function addPlanEducativoToOrganizacion($idOrganizacion, $idPlanEducativo){
        $tablaPlanOrg = $this->tablaPlanesOrganizacion;
        $select = $tablaPlanOrg->select()->from($tablaPlanOrg)->where("idOrganizacion=?",$idOrganizacion);
        $rowPlanOrg = $tablaPlanOrg->fetchRow($select);
        if(!is_null($rowPlanOrg)){
            $strIdsPlanOrg = $rowPlanOrg->idsPlanes;
            $arrIdsPlanOrg = explode(",", $strIdsPlanOrg);
            if(!in_array($idPlanEducativo, $arrIdsPlanOrg)){
                $arrIdsPlanOrg[] = $idPlanEducativo;
                $strIdsPlanOrg = implode(",", $arrIdsPlanOrg);
                $datos = array("idsPlanes"=>$strIdsPlanOrg);
                $where = $tablaPlanOrg->getAdapter()->quoteInto("idOrganizacion=?", $idOrganizacion);
                $tablaPlanOrg->update($datos, $where);
            }
        }
    }
    
    public function addCategoriasRespuestaToOrganizacion($idOrganizacion, $idCategoriasRespuesta){
        $tablaCatOrg = $this->tablaCategoriasRespOrganizacion;
        $select = $tablaCatOrg->select()->from($tablaCatOrg)->where("idOrganizacion=?",$idOrganizacion);
        $rowCatOrg = $tablaCatOrg->fetchRow($select);
        if(!is_null($rowCatOrg)){
            $strIdsCatOrg = $rowCatOrg->idsCategorias;
            $arrIdsCatOrg = explode(",", $strIdsCatOrg);
            if(!in_array($idCategoriasRespuesta, $arrIdsCatOrg)){
                $arrIdsCatOrg[] = $idCategoriasRespuesta;
                $strIdsCatOrg = implode(",", $arrIdsCatOrg);
                $datos = array("idsCategorias"=>$idCategoriasRespuesta);
                $where = $tablaCatOrg->getAdapter()->quoteInto("idOrganizacion=?", $idOrganizacion);
                $tablaCatOrg->update($datos, $where);
            }
        }
    }
}

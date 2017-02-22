<?php
/**
 * 
 */
class Encuesta_DAO_Reporte implements Encuesta_Interfaces_IReporte {
	
	private $tablaReporte;
	private $tablaERealizadas;
    private $tablaReporteEncuesta;
    
    private $tablaGrupoEscolar;
    private $tablaAsignacionGrupo;
    
    
	
	public function __construct($dbAdapter) {
	    
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaReporte = new Encuesta_Model_DbTable_ReportesEncuesta(array('db'=>$dbAdapter));
		$this->tablaERealizadas = new Encuesta_Model_DbTable_EncuestasRealizadas(array('db'=>$dbAdapter));
        $this->tablaReporteEncuesta = new Encuesta_Model_DbTable_ReportesEncuesta(array('db'=>$dbAdapter));
        
        $this->tablaGrupoEscolar = new Encuesta_Model_DbTable_GrupoEscolar(array('db'=>$dbAdapter));
        $this->tablaAsignacionGrupo = new Encuesta_Model_DbTable_AsignacionGrupo(array('db'=>$dbAdapter));
	}
	
	/**
	 * Agrega un nuevo reporte de encuesta grupal en la tabla Reporte
	 */
	public function agregarReporteGrupal($nombreReporte,$idEncuesta,$idAsignacion){
		$tablaReporte = $this->tablaReporte;
		$select = $tablaReporte->select()->from($tablaReporte)->where("nombreReporte=?",$nombreReporte);
		$rowReporte = $tablaReporte->fetchRow($select);
		$idReporte = null;
		if(is_null($rowReporte)){
			$idReporte = $tablaReporte->insert(array('idEncuesta'=>$idEncuesta,'nombreReporte'=>$nombreReporte, "tipoReporte"=>"RG", "rutaReporte"=>"",'fecha'=>date("Y-m-d H:i:s",time())));
			//$idReporte = $tablaReporte->getAdapter()->lastInsertId('Reporte','idReporteEncuesta');
		}else{
			$idReporte = $rowReporte->idReporte;
		}
		
		//$tablaERealizadas = $this->tablaERealizadas;
		//$select = $tablaERealizadas->select()->from($tablaERealizadas)->where("idEncuesta=?",$idEncuesta)->where("idAsignacionGrupo=?",$idAsignacion);
		//$rowRealizadas = $tablaERealizadas->fetchRow($select);
		
		//$rowRealizadas->idReporte = $idReporte;
		//$rowRealizadas->save();
		
		//print_r($idReporte);
		return $idReporte;
	}
	
	/**
	 * Agrega un nuevo reporte de encuesta general en la tabla Reporte
	 * Agrega una referencia en la tabla EReportesGenerales
	 */
	public function agregarReporteGeneral($nombreReporte,$idEncuesta,$idDocente){
		$tablaReporte = $this->tablaReporte;
		$select = $tablaReporte->select()->from($tablaReporte)->where("nombreReporte=?",$nombreReporte);
		$rowReporte = $tablaReporte->fetchRow($select);
		$idReporte = null;
		if(is_null($rowReporte)){
			$tablaReporte->insert(array('nombreReporte'=>$nombreReporte,'estatus'=>'A'));
			$idReporte = $tablaReporte->getAdapter()->lastInsertId('Reporte','idReporte');
		}else{
			$idReporte = $rowReporte->idReporte;
		}
		
		$tablaERealizadas = $this->tablaERealizadas;
		$select = $tablaERealizadas->select()->from($tablaERealizadas)->where("idEncuesta=?",$idEncuesta)->where("idAsignacion=?",$idAsignacion);
		$rowRealizadas = $tablaERealizadas->fetchRow($select);
		
		$rowRealizadas->idReporte = $idReporte;
		$rowRealizadas->save();
		
		//print_r($idReporte);
		return $idReporte;
	}
	
	/**
	 * 
	 */
	public function obtenerReporte($idReporte){
		$tablaReporte = $this->tablaReporteEncuesta;
		$select = $tablaReporte->select()->from($tablaReporte)->where("idReporte=?",$idReporte);
		$rowReporte = $tablaReporte->fetchRow($select);
		//$rutaReporte = PDF_PATH . '/reports/encuesta/grupal/'.$rowReporte->nombreReporte;
		//print_r($rutaReporte);
		//print_r("<br />");
		//$pdf = My_Pdf_Document::load($rutaReporte);
		//$pdf = Zend_Pdf::parse($rutaReporte);
		//echo $pdf;
		if (is_null($rowReporte)) {
			return null;
		} else {
			return $rowReporte->toArray();
		}
		
		//return $rowReporte->nombreReporte;
	}
    
	/**
     * Obtiene los reportes de evaluacion obtenidos por un docente en el ciclo escolar especificado.
     */
	public function getReportesDocenteByIdCiclo($idCicloEscolar,$idDocente) {
		$tablaReporte = $this->tablaReporteEncuesta;
        $tablaGE = $this->tablaGrupoEscolar;
        // Solo seleccionamos los ids de los grupos escolares
        $select = $tablaGE->select()->from($tablaGE,array('idGrupoEscolar'))->where("idCicloEscolar=?",$idCicloEscolar);
        //print_r($select->__toString());
        $idsGruposEscolares = $tablaGE->fetchAll($select);
        //$idsGruposEscolares = array();
        $tablaAG = $this->tablaAsignacionGrupo;
        $select = $tablaAG->select()->from($tablaAG,array('idAsignacionGrupo'))->where("idGrupoEscolar IN (?)",$idsGruposEscolares->toArray())->where("idRegistro=?",$idDocente);
        $idsAsignacion = $tablaAG->fetchAll($select);
        //print_r($select->__toString());
        $tablaER = $this->tablaERealizadas;
        //$select = $tablaER->select()->from($tablaER)->where("idAsignacionGrupo IN (?)",$idsAsignacion->toArray());
        //print_r($select->__toString());
        //$idsEncuestasR =
        $select = $tablaReporte->select()->from($tablaReporte)->where("idAsignacionGrupo IN (?)",$idsAsignacion->toArray());
        $rowsReportes = $tablaReporte->fetchAll($select);
        //print_r($select->__toString());
        if(!is_null($rowsReportes)){
            return $rowsReportes->toArray();
        }
         
	}
}

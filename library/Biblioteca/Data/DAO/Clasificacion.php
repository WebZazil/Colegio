<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Clasificacion {
	
    private $tableClasificacion;
    
	function __construct() {
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		$this->tableClasificacion = new Biblioteca_Data_DbTable_Clasificacion(array("db"=>$dbAdapter));
	}
    
    public function getAllClasificaciones() {
        $tablaClasif = $this->tableClasificacion;
        $select = $tablaClasif->select()->from($tablaClasif)->order("clasificacion ASC");
        $rowsClasificaciones = $tablaClasif->fetchAll($select);
        
        return $rowsClasificaciones->toArray();
    }
    
    public function getClasificacionById($idClasificacion) {
        $tablaClasif = $this->tableClasificacion;
        $select = $tablaClasif->select()->from($tablaClasif)->where("idClasificacion=?",$idClasificacion);
        $rowClasificacion = $tablaClasif->fetchRow($select);
        
        if (is_null($rowClasificacion)) {
            return null;
        } else {
            return $rowClasificacion->toArray();
        }
        
        
    }
    
	
	public function addClasificacion($data)
	{
		$this->tableClasificacion->insert($data);
	}
	
	public function getEditorialByParamas(array $params){
	    $tablaClasificacion = $this->tableClasificacion;
	    $select = $tablaClasificacion->select()->from($tablaClasificacion);
	    
	    if(!empty($params)){
	        foreach ($params as $key => $value) {
	            $select->where($key."=?", $value);
	        }
	    }
	    
	    //print_r($select->__toString());
	    
	    $clasificaciones = $tablaClasificacion->fetchAll($select);
	    return $clasificaciones->toArray();
	    
	    
	}
    
    
}

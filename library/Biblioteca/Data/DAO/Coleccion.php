<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Coleccion {
	
    private $tableColeccion;
    
	function __construct($dbAdapter) {
	    $config = array('db' => $dbAdapter);
		//$dbAdapter = Zend_Registry::get("dbmodqueryb");
        $this->tableColeccion = new Biblioteca_Data_DbTable_Coleccion($config);
	}
    
    public function getAllColecciones() {
        $tablaColeccion = $this->tableColeccion;
        $select = $tablaColeccion->select()->from($tablaColeccion)->order("coleccion ASC");
        $rowsColecciones = $tablaColeccion->fetchAll($select);
        
        return $rowsColecciones->toArray();
    }
    
    public function getColeccionById($idColeccion) {
        $tablaColeccion = $this->tableColeccion;
        $select = $tablaColeccion->select()->from($tablaColeccion)->where("idColeccion=?",$idColeccion);
        $rowColeccion = $tablaColeccion->fetchRow($select);
        
        if (is_null($rowColeccion)) {
            return null;
        } else {
            return $rowColeccion->toArray();
        }
    }
	
	
	public function addColeccion($data) {
        $this->tableAutor->insert($data);
    }
    
    public function getEditorialByParamas(array $params){
        $tablaColeccion = $this->tableColeccion;
        $select = $tablaColeccion->select()->from($tablaColeccion);
        
        if(!empty($params)){
            foreach ($params as $key => $value) {
                $select->where($key."=?", $value);
            }
        }
        
        //print_r($select->__toString());
        
        $colecciones = $tablaColeccion->fetchAll($select);
        return $colecciones->toArray();
        
        
    }
    
    
    
    public function editarColeccion($idColeccion, array $datos){
        $tCol = $this->tableColeccion;
        $where = $tCol->getAdapter()->quoteInto("idColeccion=?", $idColeccion);
        $tCol->update($datos, $where);
    }
    
}

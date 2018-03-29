<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Tema {
    
    private $tableTema;
	
	function __construct($dbAdapter) {
	//	$dbAdapter = Zend_Registry::get("dbmodqueryb");
	
	    $config = array('db' =>$dbAdapter);
		$this->tableTema = new Biblioteca_Data_DbTable_Tema($config);
	}
    
    public function getTemaById($idTema) {
        $tablaTema = $this->tableTema;
        $select = $tablaTema->select()->from($tablaTema)->where("idTema=?",$idTema);
        $rowTema = $tablaTema->fetchRow($select);
        
        if (is_null($rowTema)) {
            return null;
        } else {
            return $rowTema->toArray();
        }
        
    }
    
    public function getAllTemas() {
        $tablaTema = $this->tableTema;
        $select = $tablaTema->select()->from($tablaTema)->order("tema ASC");
        //$statement = $select->query();
        $rowsTema = $tablaTema->fetchAll($select);
        
        
        if (is_null($rowsTema)) {
            return null;
        } else {
            return $rowsTema->toArray();
        }
        
    }
	
	public function addTema($data) {
        $this->tableTema->insert($data);
    }
    
    public function getEditorialByParamas(array $params){
        $tablaTema = $this->tableTema;
        $select = $tablaTema->select()->from($tablaTema);
        
        if(!empty($params)){
            foreach ($params as $key => $value) {
                $select->where($key."=?", $value);
            }
        }
        
        //print_r($select->__toString());
        
        $temas = $tablaTema->fetchAll($select);
        return $temas->toArray();
        
        
    }
    
    public function editarTema($idTema, array $datos){
        $tT = $this->tableTema;
        $where = $tT->getAdapter()->quoteInto("idTema=?", $idTema);
        $tT->update($datos, $where);
    }
    
}

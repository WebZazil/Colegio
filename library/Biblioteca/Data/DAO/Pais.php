<?php
/**
 * 
 */
class Biblioteca_Data_DAO_Pais {
    
    private $tablePais;
	
	function __construct($dbAdapter) {
	//	$dbAdapter = Zend_Registry::get("dbmodqueryb");
	
	    $config = array('db' =>$dbAdapter);
		$this->tablePais = new Biblioteca_Model_DbTable_Pais($config);
	}
    
    public function getPaisById($idPais) {
        $tablaPais = $this->tablePais;
        $select = $tablaPais->select()->from($tablaPais)->where("idPais=?",$idPais);
        $rowPais = $tablaPais->fetchRow($select);
        
        if (is_null($rowPais)) {
            return null;
        } else {
            return $rowPais->toArray();
        }
        
    }
    
    public function getAllPaises() {
        $tablaPais = $this->tablePais;
        $select = $tablaPais->select()->from($tablaPais)->order("nombre ASC");
        //$statement = $select->query();
        $rowsPais = $tablaPais->fetchAll($select);
        
        
        if (is_null($rowsPais)) {
            return null;
        } else {
            return $rowsPais->toArray();
        }
        
    }
	
	public function addPais($data) {
        $this->tablePais->insert($data);
    }
    
    public function getPaisByParamas(array $params){
        $tablaPais = $this->tablePais;
        $select = $tablaPais->select()->from($tablaPais);
        
        if(!empty($params)){
            foreach ($params as $key => $value) {
                $select->where($key."=?", $value);
            }
        }
        
        //print_r($select->__toString());
        
        $temas = $tablaTema->fetchAll($select);
        return $temas->toArray();
        
        
    }
    
    public function editarPais($idPais, array $datos){
        $tP = $this->tablePais;
        $where = $tP->getAdapter()->quoteInto("idPais=?", $idPais);
        $tP->update($datos, $where);
    }
    
}
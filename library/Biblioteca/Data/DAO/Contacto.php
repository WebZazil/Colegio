<?php
/**
 * 
 * @author Alizon
 *
 */
class Biblioteca_Data_DAO_Contacto 
{
    private $tableContacto;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableContacto = new Biblioteca_Data_DbTable_Contacto($config);
    }
    
    
    public function getAllContactos() {
        $tablaContacto = $this->tableContacto;
        
        $rowsContacto = $tablaContacto->fetchAll();
        
        return $rowsContacto->toArray();
    }
    
    public function getContactoById($idContacto) {
        $tC = $this->tableContacto;
        $select = $tC->select()->from($tC)->where('idContacto=?',$idContacto);
        $rowContacto = $tC->fetchRow($select);
        
        return $rowContacto->toArray();
    }
    
    public function editarContacto($idContacto, array $datos){
        $tC = $this->tableContacto;
        $where = $tC->getAdapter()->quoteInto("idContacto=?", $idContacto);
        $tC->update($datos, $where);
    }
    
}

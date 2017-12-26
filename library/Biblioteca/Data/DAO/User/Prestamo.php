<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_User_Prestamo {
    
    private $tablePrestamo;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePrestamo = new Biblioteca_Data_DbTable_User_Prestamo($config);
    }
    
    public function getPrestamosUsuario($idUsuario,$idEstatus) {
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)
            ->where('idUsuario=?',$idUsuario)
            ->where('idEstatus=?',$idEstatus);
        
        $rowsPrestamos = $tP->fetchAll($select);
        return $rowsPrestamos->toArray();
    }
}
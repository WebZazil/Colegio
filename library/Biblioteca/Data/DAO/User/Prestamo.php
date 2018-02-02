<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_User_Prestamo {
    
    private $tablePrestamo;
    private $tableEstatusPrestamo;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePrestamo = new Biblioteca_Data_DbTable_User_Prestamo($config);
        $this->tableEstatusPrestamo = new Biblioteca_Data_DbTable_EstatusPrestamo($config);
    }
    
    public function getPrestamosUsuario($idUsuario) {
        $tEP = $this->tableEstatusPrestamo;
        $select = $tEP->select()->from($tEP)->where('estatusPrestamo=?','ENTREGADO');
        $rowEP = $tEP->fetchRow($select)->toArray();
        
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)
            ->where('idUsuario=?',$idUsuario)
            ->where('idEstatusPrestamo <> ?',$rowEP['idEstatusPrestamo']);
        
        $rowsPrestamos = $tP->fetchAll($select);
        return $rowsPrestamos->toArray();
    }
    
    public function getAllEstatusPrestamo() {
        $tEP = $this->tableEstatusPrestamo;
        return $tEP->fetchAll()->toArray();
    }
}
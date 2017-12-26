<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_User_EstatusLibro {
    
    private $tablaEstatusLibro;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablaEstatusLibro = new Biblioteca_Data_DbTable_User_EstatusLibro($config);
    }
    
    /**
     * 
     * @return array
     */
    public function getEstatusLibroUsuario() {
        $tEL = $this->tablaEstatusLibro;
        $select = $tEL->select()->from($tEL)->where('tipoUsuario=?','US');
        return $this->tablaEstatusLibro->fetchAll($select)->toArray();
    }
}
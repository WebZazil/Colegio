<?php
class Biblioteca_Data_DAO_Usuario {
    
    private $tableUsuario;
    private $tableAdmin;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableUsuario = new Biblioteca_Data_DbTable_Usuario($config);
        $this->tableAdmin = new Biblioteca_Data_DbTable_Admin($config);
    }
    
    public function getUsuarioBibliotecaById($idUsuario) {
        $tU = $this->tableUsuario;
        $select = $tU->select()->from($tU)->where('id=?',$idUsuario);
        $rowUsuario = $tU->fetchRow($select);
        
        return $rowUsuario->toArray();
    }
    
    public function getUsuariosSistema() {
        $tA = $this->tableAdmin;
        return $tA->fetchAll()->toArray();
    }
    
    public function getUsuariosBiblioteca() {
        $tU = $this->tableUsuario;
        $select = $tU->select()->from($tU)->where('estatus=?','ACTIVO');
        $rowsUsuarios = $tU->fetchRow($select);
        return $rowsUsuarios->toArray();
    }
    
    public function getUsuariosBibliotecaByTipo($pattern) {
        $tU = $this->tableUsuario;
        $select = $tU->select()->from($tU)
            ->where('nickname like ?', '%'.$pattern.'%')
            ->orWhere('nombres like ?','%'.$pattern.'%')
            ->where('estatus=?', 'ACTIVO');
        
        $rowsUsuarios = $tU->fetchAll($select);
        
        return $rowsUsuarios->toArray();
    }
    
}
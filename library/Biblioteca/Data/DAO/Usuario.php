<?php
class Biblioteca_Data_DAO_Usuario {
    
    private $tableUsuario;
    private $tableAdmin;
    private $tableContacto;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableUsuario = new Biblioteca_Data_DbTable_Usuario($config);
        $this->tableAdmin = new Biblioteca_Data_DbTable_Admin($config);
        $this->tableContacto = new Biblioteca_Data_DbTable_Contacto($config);
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
    
    public function getUsuariosBibliotecaByTipo($pattern,$tipo) {
        $tU = $this->tableUsuario;
        $select = $tU->select()->from($tU);
        if ($pattern != '') {
            $select->where('nickname like ?', '%'.$pattern.'%')
            ->orWhere('nombres like ?','%'.$pattern.'%')
            ->orWhere('apaterno like ?','%'.$pattern.'%')
            ->orWhere('amaterno like ?','%'.$pattern.'%');
        }
        
        $select->where('estatus=?', 'ACTIVO')->where('tipoUsuario=?', $tipo);
        //print_r($select->__toString());
        
        $rowsUsuarios = $tU->fetchAll($select);
        
        return $rowsUsuarios->toArray();
    }
    
    public function agregarContacto($datos) {
        $tC = $this->tableContacto;
        return $tC->insert($datos);
    }
    
    public function agregarUsuarioBiblioteca($datos) {
        $tU = $this->tableUsuario;
        return $tU->insert($datos);
    }
    
}
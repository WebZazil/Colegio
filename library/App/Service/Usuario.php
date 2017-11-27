<?php
class App_Service_Usuario {
    
    private $usuarioDAO;
    
    public function __construct($dbAdapter) {
        $this->usuarioDAO = new App_Data_DAO_Usuario($dbAdapter);
    }
    
    public function obtenerUsuariosByIdOrganizacion($idOrganizacion) {
        return $this->usuarioDAO->getUsuariosOrganizacion($idOrganizacion);
    }
    
    public function obtenerUsuarioById($idUsuario) {
        return $this->usuarioDAO->getUsuarioById($idUsuario);
    }
    
    public function obtenerRolUsuario($idUsuario) {
        return $this->usuarioDAO->getRolUsuario($idUsuario);
    }
    
    public function obtenerRoles() {
        return $this->usuarioDAO->getRoles();
    }
    
    public function obtenerRolById($idRol) {
        return $this->usuarioDAO->getRolById($idRol);
    }
    
    public function editarRol($datos, $idRol) {
        $this->usuarioDAO->editRol($datos, $idRol);
    }
}
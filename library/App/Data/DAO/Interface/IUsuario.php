<?php
//namespace library\App\Data\DAO\Interface;

/**
 *
 * @author EnginnerRodriguez
 *        
 */
interface App_Data_DAO_Interface_IUsuario {
    
    public function getUsuarioById($idUsuario);
    public function getUsuariosOrganizacion($idOrganizacion);
    public function addUsuarioOrganizacion($container);
    public function editUsuarioOrganizacion($idOrganizacion, $container);
    public function deleteUsuarioOrganizacion($container);
    
    public function getRolUsuario($idUsuario);
}


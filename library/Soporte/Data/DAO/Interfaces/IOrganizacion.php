<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
interface Soporte_Data_DAO_Interfaces_IOrganizacion {
    public function getSubscripcionesOrganizacion($idModulo, $idOrganizacion, $tipo = 'Q');
    public function getUsuariosOrganizacion($idOrganizacion);
    public function getRolUsuario($idUsuario);
    
}
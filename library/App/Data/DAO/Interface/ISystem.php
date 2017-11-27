<?php
//namespace library\App\Data\DAO\Interface;

/**
 *
 * @author EnginnerRodriguez
 *        
 */
interface App_Data_DAO_Interface_ISystem {
    // ----------------------------------------------------   Modules
    public function getModules();
    public function getModule($idModule);
    public function addModule($data, $config);
    public function editModule($data, $config);
    // ----------------------------------------------------   Modules by Organizacion
    public function getModulesByIdOrganizacion($idOrganizacion);
    
    // ----------------------------------------------------   Subscriptions
    public function getSubscripcionesOrganizacion($idOrganizacion);
    
    
}


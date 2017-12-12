<?php
//namespace library\Encuesta\Data\DAO\Interface;

/**
 *
 * @author EnginnerRodriguez
 *        
 */
interface Encuesta_Data_DAO_Interface_ICategoria {
    
    public function getAllCategorias();
    public function getCategoriaById($idCategoria);
    
    public function getOpcionesCategoria($idCategoria);
    public function getOpcionById($idOpcion);
    public function getMaxOpcionCategoria($idCategoria, $tipoVal);
    public function getMinOpcionCategoria($idCategoria, $tipoVal);
    
}


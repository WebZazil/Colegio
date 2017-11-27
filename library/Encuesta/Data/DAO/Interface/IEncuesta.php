<?php
/**
 *
 * @author EnginnerRodriguez
 *        
 */
interface Encuesta_Data_DAO_Interface_IEncuesta {
    
    public function getAllEncuesta();
    public function getEncuestaById($idEncuesta);
    public function getEncuestaByStatus($status);
    public function getEncuestaByUpperDate($date);
    public function getEncuestaByLowerDate($date);
    
}


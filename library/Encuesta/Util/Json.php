<?php
/**
 * 
 */
class Encuesta_Util_Json {
	
	function __construct() {
		
	}
    
    /**
     * Normaliza la encuesta de Orientadoras
     * Obtenemos un array unidimensional asociativo con idPregunta - idRespuesta como claves valor
     */
    public function processJsonEncuestaDos($json) {
        $obj = str_replace("\\", "", $json);
        $str = substr($obj, 1, -1);
        $jsonArray = json_decode($str,true);
        //print_r($jsonArray);
        $arrayUnidimensional = array();
        foreach ($jsonArray as $idFase => $fase) {
            //print_r($idFase); print_r("<br /><br />");
            //print_r($fase); print_r("<br /><br />");
            if ($idFase == "pss") {
                foreach ($fase as $idPregunta => $idRespuesta) {
                    $arrayUnidimensional[$idPregunta] = $idRespuesta;
                }
            }elseif($idFase == "pab"){
                $arrayUnidimensional[32] = $fase;
            }
        }
        //print_r($arrayUnidimensional);
        return $arrayUnidimensional;
    }
    
    /**
     * Normaliza la encuesta de ClimaEscolar de Preparatoria
     * Obtenemos un array unidimensional asociativo con idPregunta - idRespuesta como claves valor
     */
    public function processJsonEncuestaTres($json) {
        $obj = str_replace("\\", "", $json);
        $str = substr($obj, 1, -1);
        $jsonArray = json_decode($str,true);
        $arrUni = array();
        foreach ($jsonArray as $fase) {
            //print_r($fase); print_r("<br />---- <br />");
            foreach ($fase as $idPregunta => $opcion) {
                $arrUni[$idPregunta] = $opcion;
            }
            //break;
        }
        //print_r("Unidemensional: ");
        //print_r($arrUni);
        
        return $arrUni;
    }
    
    /**
     * Normaliza la encuesta de Docentes
     * Obtenemos un array unidimensional asociativo con idPregunta - idRespuesta como claves valor
     */
    public function processJsonEncuestaCuatro($json) {
        $obj = str_replace("\\", "", $json);
        $str = substr($obj, 1, -1);
        $jsonArray = json_decode($str,true);
        $arrUni = array();
        foreach ($jsonArray as $fase) {
            //print_r($fase); print_r("<br />---- <br />");
            foreach ($fase as $idPregunta => $opcion) {
                $arrUni[$idPregunta] = $opcion;
            }
            //break;
        }
        //print_r($arrUni);
        return $arrUni;
    }
    
}

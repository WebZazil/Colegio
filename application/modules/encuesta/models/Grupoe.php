<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_Model_Grupoe
{
	private $idGrupo;

    public function getIdGrupo() {
        return $this->idGrupo;
    }
    
    public function setIdGrupo($idGrupo) {
        $this->idGrupo = $idGrupo;
    }

    private $idGrado;

    public function getIdGrado() {
        return $this->idGrado;
    }
    
    public function setIdGrado($idGrado) {
        $this->idGrado = $idGrado;
    }

    private $idCiclo;

    public function getIdCiclo() {
        return $this->idCiclo;
    }
    
    public function setIdCiclo($idCiclo) {
        $this->idCiclo = $idCiclo;
    }

    private $grupo;

    public function getGrupo() {
        return $this->grupo;
    }
    
    public function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    public function __construct(array $datos)
    {
        if(array_key_exists("idGrupoEscolar", $datos)) $this->idGrupo = $datos["idGrupoEscolar"];
		if(array_key_exists("idGradoEducativo", $datos)) $this->idGrado = $datos["idGradoEducativo"];
		if(array_key_exists("idCicloEscolar", $datos)) $this->idCiclo = $datos["idCicloEscolar"];
		$this->grupo = utf8_encode($datos["grupoEscolar"]);
    }
	
	public function toArray()
	{
		$datos = array();
		
		$datos["idGrupoEscolar"] = $this->idGrupo;
		$datos["idGradoEducativo"] = $this->idGrado;
		$datos["idCicloEscolar"] = $this->idCiclo;
		$datos["grupoEscolar"] = $this->grupo;
		
		return $datos;
	}
}


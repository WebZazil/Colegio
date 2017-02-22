<?php
/**
 * @author Hector Giovanni Rodriguez Ramos
 * @copyright 2016, Zazil Consultores S.A. de C.V.
 * @version 1.0.0
 */
class Encuesta_DAO_Grupo implements Encuesta_Interfaces_IGrupo {
	
	private $tablaSeccionEncuesta;
	private $tablaGrupoSeccion;
	private $tablaPregunta;
	private $tablaOpcionCategoria;
		
	public function __construct($dbAdapter) {
		//$dbAdapter = Zend_Registry::get('dbmodencuesta');
		
		$this->tablaSeccionEncuesta = new Encuesta_Model_DbTable_SeccionEncuesta(array('db'=>$dbAdapter));
		$this->tablaGrupoSeccion = new Encuesta_Model_DbTable_GrupoSeccion(array('db'=>$dbAdapter));
		$this->tablaPregunta = new Encuesta_Model_DbTable_Pregunta(array('db'=>$dbAdapter));
		$this->tablaOpcionCategoria = new Encuesta_Model_DbTable_OpcionCategoria(array('db'=>$dbAdapter));
	}
	// =====================================================================================>>>   Buscar
	/*
	public function obtenerGrupo($idGrupo) {
		$tablaGrupo = $this->tablaGrupoEncuesta;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupo = ?", $idGrupo);
		$rowGrupo = $tablaGrupo->fetchRow($select);
		
		$modelGrupo = new Encuesta_Model_Grupo($rowGrupo->toArray());
		
		return $modelGrupo;
	}
	*/
	/*
	public function obtenerPreguntas($idGrupo) {
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "G")->where("idOrigen = ?", $idGrupo);
		$rowsPreguntas = $tablaPregunta->fetchAll($select);
		$modelPreguntas = array();
		
		if(!is_null($rowsPreguntas)){
			foreach ($rowsPreguntas as $row) {
				$modelPregunta = new Encuesta_Model_Pregunta($row->toArray());
				$modelPreguntas[] = $modelPregunta;
			}
		}
		
		return $modelPreguntas;
	}
	*/
	
	/**
	 * Un grupo con preguntas de simple seleccion comparte las mismas opciones 
	 */
	public function obtenerValorMayorOpcion($idGrupo){
		$tablaGrupo = $this->tablaGrupoSeccion;
		$select = $tablaGrupo->select()->where("idGrupoSeccion=?",$idGrupo);
		$rowGrupo = $tablaGrupo->fetchRow($select);
        
        //print_r("Máximo: ".$rowGrupo->valorMaximo."<br />");
		/*
		$tablaOpcion = $this->tablaOpcionCategoria;
		$ids = explode(",", $grupo->opciones);
		$select = $tablaOpcion->select()->from($tablaOpcion,array("idOpcionCategoria", "valor"=>"MAX(valorEntero)"))->where("idOpcionCategoria IN (?)",$ids);
		
		$row = $tablaOpcion->fetchRow($select);
		return $row->toArray();
        */
        return $rowGrupo->toArray();
	}
	
	public function obtenerValorMenorOpcion($idGrupo){
		
	}
	
	// =====================================================================================>>>   Crear
	/*
	public function crearGrupo($idSeccion, Encuesta_Model_Grupo $grupo) {
		$tablaGrupo = $this->tablaGrupoEncuesta;
		$tablaSeccion = $this->tablaSeccionEncuesta;
		
		$select = $tablaSeccion->select()->from($tablaSeccion)->where("idSeccionEncuesta = ?", $idSeccion);
		
		$seccion = $tablaSeccion->fetchRow($select);
		$seccion->elementos++;
		
		$grupo->setOrden($seccion->elementos);
		
		try{
			$tablaGrupo->insert($grupo->toArray());
			$seccion->save();
			return $modelGrupo->getIdGrupo();
		}catch(Exception $ex){
			throw new Exception($ex->getMessage(), 1);
		}
	}
	*/
	// =====================================================================================>>>   Editar
	/*
	public function editarGrupo($idGrupo, array $grupo) {
		$tablaGrupo = $this->tablaGrupoEncuesta;
		$where = $tablaGrupo->getAdapter()->quoteInto("idGrupo = ?", $idGrupo);
		
		$tablaGrupo->update($grupo, $where);
	}
	*/
	// =====================================================================================>>>   Eliminar
	/*
	public function eliminarGrupo($idGrupo) {
		$tablaGrupo = $this->tablaGrupoEncuesta;
		$where = $tablaGrupo->getAdapter()->quoteInto("idGrupoEncuesta = ?", $idGrupo);
		
		$tablaGrupo->delete($where);
	}
	*/
	public function eliminarPreguntas($idGrupo){
		$tablaPregunta = $this->tablaPregunta;
		$where = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "G")->where("idOrigen = ?", $idGrupo);
		
		$tablaPregunta->delete($where);
	}
	
	// **************************************************************************************** IMPLEMENTANDO ESTANDAR DE NOMBRES
	/**
	 * function getGruposByIdSeccion($idSeccion) - 
	 * @param $idSeccion - 
	 * @param array - conjunto de models Encuesta_Models_Grupo
	 */
	public function getGruposByIdSeccion($idSeccion){
		$tablaGrupo = $this->tablaGrupoSeccion;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idSeccionEncuesta=?",$idSeccion);
		$rowsGrupos = $tablaGrupo->fetchAll($select);
		
		$modelGrupos = array();
		foreach ($rowsGrupos as $row) {
			$model = new Encuesta_Models_Grupo($row->toArray());
			
			$modelGrupos[] = $model;
		}
		
		return $modelGrupos;
	}
	
	/**
	 * function getGrupoById($id) - 
	 * @param $id - El id de grupo a consultar
	 * @return $modelGrupo | null - El model o null conseguido de la base de datos
	 */
	public function getGrupoById($id){
		$tablaGrupo = $this->tablaGrupoSeccion;
		$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoSeccion = ?", $id);
		$rowGrupo = $tablaGrupo->fetchRow($select);
		
		if(is_null($rowGrupo)){
			return null;
		}else{
			$modelGrupo = new Encuesta_Models_Grupo($rowGrupo->toArray());
			return $modelGrupo;
		}
	}
	
	/**
	 * function getPreguntasByIdGrupo($idGrupo) - Obtiene las preguntas correspondientes al contenedor grupo especificado mediante el $id
	 * @param $idGrupo - 
	 * 
	 */
	public function getPreguntasByIdGrupo($idGrupo) {
		$tablaPregunta = $this->tablaPregunta;
		$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "G")->where("idOrigen = ?", $idGrupo);
		$rowsPreguntas = $tablaPregunta->fetchAll($select);
		$modelPreguntas = array();
		
		if(!is_null($rowsPreguntas)){
			foreach ($rowsPreguntas as $row) {
				$modelPregunta = new Encuesta_Models_Pregunta($row->toArray());
				$modelPreguntas[] = $modelPregunta;
			}
		}
		
		return $modelPreguntas;
	}
	
	/**
	 * function addGrupoToSeccion(Encuesta_Models_Grupo $grupo)
	 * @param $grupo - model Encuesta_Models_Grupo
	 */
	public function addGrupoToSeccion(Encuesta_Models_Grupo $grupo) {
		
		$tablaGrupo = $this->tablaGrupoSeccion;
		
		$datos = $grupo->toArray();
		unset($datos["fecha"]);
		$tablaGrupo->insert($datos);
		// Si se agrego un grupo a una seccion en la seccion se debe incrementar el numero de elementos
		$tablaSeccion = $this->tablaSeccionEncuesta;
		$select = $tablaSeccion->select()->from($tablaSeccion)->where("idSeccionEncuesta=?",$grupo->getIdSeccionEncuesta());
		$rowSeccion = $tablaSeccion->fetchRow($select);
		$rowSeccion->elementos++;
		$rowSeccion->save();
	}
	
	/**
	 * 
	 */
	public function editGrupo($id, array $datos){
		$tablaGrupo = $this->tablaGrupoSeccion;
		$where = $this->tablaGrupoSeccion->getAdapter()->quoteInto("idGrupoSeccion=?", $id);
		$tablaGrupo->update($datos, $where);
	}
    
    /**
     * Metodo general.
     * Obtiene el valor máximo de las opciones de respuesta (T.OpcionCategoria).
     */
    public function normalizarValorMaximo() {
        $tablaGrupoS = $this->tablaGrupoSeccion;
        $rowsGrupos = $tablaGrupoS->fetchAll();
        $tablaOpcionCat = $this->tablaOpcionCategoria;
        $message = "Número de grupos a normalizar: <strong>".count($rowsGrupos)."</strong>";
        echo "<div>".$message."</div>";
        $counter = 0;
        try{
            foreach ($rowsGrupos as $rowGrupo) {
                $counter++;
                if (!is_null($rowGrupo->opciones)) {
                    $idsOpciones = explode(",", $rowGrupo->opciones);
                    $select = $tablaOpcionCat->select()->from($tablaOpcionCat)->where("idOpcionCategoria IN (?)",$idsOpciones);
                    //print_r($select->__toString());
                    //print_r("<br />");
                    $rowsOpcion = $tablaOpcionCat->fetchAll($select);
                    $valMax = 0;
                    foreach ($rowsOpcion as $rowOpcion) {
                        if($rowOpcion->valorEntero > $valMax) $valMax = $rowOpcion->valorEntero;
                    }
                    print_r("Normalizacion: <strong>".$counter."</strong> Valor Maximo: ".$valMax."<br />");
                    $rowGrupo->valorMaximo = $valMax;
                    $rowGrupo->save();
                }
            }
            $message = "Normalizacion finalizada exitosamente!!";
        }catch(Exception $ex){
            $message = $ex->getMessage();
        }
        
        return $message;
    }

    /**
     * 
     */
    public function normalizarValorMinimo() {
        $tablaGrupoS = $this->tablaGrupoSeccion;
        $rowsGrupos = $tablaGrupoS->fetchAll();
        $tablaOpcionCat = $this->tablaOpcionCategoria;
        $message = "Número de grupos a normalizar: <strong>".count($rowsGrupos)."</strong>";
        echo "<div>".$message."</div>";
        $counter = 0;
        try{
            foreach ($rowsGrupos as $rowGrupo) {
                $counter++;
                if (!is_null($rowGrupo->opciones)) {
                    $idsOpciones = explode(",", $rowGrupo->opciones);
                    $select = $tablaOpcionCat->select()->from($tablaOpcionCat)->where("idOpcionCategoria IN (?)",$idsOpciones);
                    //print_r($select->__toString());
                    //print_r("<br />");
                    $rowsOpcion = $tablaOpcionCat->fetchAll($select);
                    $valMin = 1;
                    foreach ($rowsOpcion as $rowOpcion) {
                        if($rowOpcion->valorEntero < $valMin) $valMin = $rowOpcion->valorEntero;
                    }
                    print_r("Normalizacion: <strong>".$counter."</strong> Valor Minimo: ".$valMin."<br />");
                    $rowGrupo->valorMinimo = $valMin;
                    $rowGrupo->save();
                }
            }
            $message = "Normalizacion finalizada exitosamente!!";
        }catch(Exception $ex){
            $message = $ex->getMessage();
        }
        return $message;
    }
	
	
}

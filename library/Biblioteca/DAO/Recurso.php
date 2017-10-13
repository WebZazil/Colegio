<?php

/**
 * 
 */
class Biblioteca_DAO_Recurso implements Biblioteca_Interfaces_IRecurso {
		
	private $tableRecurso;
	
	private $tableAutor;
	private $tableMaterial;
	private $tableColeccion;
	private $tableClasificacion;
	
	function __construct(){
		
		$dbAdapter = Zend_Registry::get("dbmodqueryb");
		
		$this->tableRecurso = new Biblioteca_Model_DbTable_Recurso(array('db'=>$dbAdapter));
		
		$this->tableMaterial = new Biblioteca_Data_DbTable_Material(array('db'=>$dbAdapter));
		$this->tableColeccion =new Biblioteca_Data_DbTable_Coleccion(array('db'=>$dbAdapter));
		$this->tableClasificacion = new Biblioteca_Data_DbTable_Clasificacion(array('db'=>$dbAdapter));
		$this->tableAutor = new Biblioteca_Data_DbTable_Autor(array('db'=>$dbAdapter));
		
	}

	 
	 public function agregarRecurso(Biblioteca_Model_Recurso $recurso){
		
		 $tablaRecurso = $this->tableRecurso;
		 $tablaRecurso->insert($recurso->toArray());
	 }
	 
	 public function getAllRecursos(){
	 		
		$tablaRecurso = $this->tableRecurso;
		$rowsRecurso = $tablaRecurso->fetchAll();
			
			if(!is_null($rowsRecurso)){
				$arrRecursos = $rowsRecurso->toArray();
				$arrModelRecurso = array();
						foreach ($arrRecursos as $arrRecurso) {
						$modelRecurso = new Biblioteca_Model_Recurso($arrRecurso);
						$arrModelRecurso[] = $modelRecurso;
						}
				return $arrModelRecurso;
			}else{
				return array();
			}
		}
		
	public function getAllTableRecursos(){
 		
		$tablaRecurso = $this->tableRecurso;
		$rowsRecurso = $tablaRecurso->fetchAll();
	
	 	return $rowsRecurso->toArray();
	
    }
		
	public function getDescripciones() {
		$tablaRecurso = $this->tableRecurso;
		$select = $tablaRecurso->select()
		->setIntegrityCheck(false)
		->from($tablaRecurso, array('titulo','subtitulo'))
		->join('Material', 'Recurso.idMaterial = Material.idMaterial', ('Material.material'));
		//->join('Coleccion', 'Recurso.idColeccion = Coleccion.idColeccion', ('Coleccion.coleccion'))
		//->join('Clasificacion', 'Recurso.idClasificacion = Clasificacion.idClasificacion', ('Clasificacion.clasificacion'));
		//$rowRecursos = $tablaRecurso->fetchAll();
		print_r("$select");
		return $tablaRecurso->fetchAll();
		
	}
	
	/**
	 * Get all Material in T.Material by $idsRecurso
	 * @param $idsRecurso
	 * @return array() of T.Material
	 */
	public function getMaterialByIdsRecurso(array $idsRecurso) {
		$ids = array_values($idsRecurso);
		
		$tablaMaterial = $this->tableMaterial;
		$select = $tablaMaterial->select()->from($tablaMaterial)->where("idRecurso IN (?)", $ids);
		$rowsMaterial = $tablaMaterial->fetchAll($select)->toArray();
		
		return $rowsMaterial;
	}
	
	public function getColeccionesByIdsRecurso(array $idsRecurso)
	{
		$ids = array_values($idsRecurso);	
		
		$tablaColeccion = $this->tableColeccion;
		$select = $tablaColeccion->select()->from($tablaColeccion)->where("idRecurso IN (?), $ids");
		$rowsColeccion = $tablaColeccion->fetchAll($select)->toArray();
		
		return $rowsColeccion;
	}
	
	public function buscar($consulta)
	{
		$tablaRecurso = $this->tableRecurso;
		$select = $tablaRecurso->select()->from($tablaRecurso)->where('(titulo LIKE "%'.$consulta.'%")OR (material LIKE "%'.$consulta.'%")');
		
		return $tablaRecurso->fetchAll($select);
	}
	
	/**
	 * 
	 */
	public function getRecursoByParams(array $params) {
		$tRecurso = $this->tableRecurso;
		$select = $tRecurso->select()->from($tRecurso);
		
		if(!empty($params)){
			foreach ($params as $key => $value) {
				$select->where($key."=?", $value);
			}
		}
		
		print_r($select->__toString());
		
		$recursos = $tRecurso->fetchAll($select);
		return $recursos->toArray();
	}
	
	
	
	
}
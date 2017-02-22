<?php
/**
 * Clase que opera sobre libros en la biblioteca
 * @author Alizon Fernanda Díaz
 * @copyright 2016
 * @version 1.0.0
 */
class Biblioteca_DAO_Libro implements Biblioteca_Interfaces_ILibro {
	
	private $tablaLibro;
	
	function __construct() {
		//$dbAdapter = Zend_Registry::get('dbmodgeneral');
		$dbAdapter = Zend_Registry::get("dbgenerale");
		
		$this->tablaLibro = new Biblioteca_Model_DbTable_Libro(array('db'=>$dbAdapter));
	}
	
	/**
	 * Función que agrega un libro al catalogo de libros
	 * @param $libro libro a agregar
	 */
	public function agregarLibro(Biblioteca_Model_Libro $libro){//firma de la función
		
		$tablaLibro = $this->tablaLibro;
		$tablaLibro->insert($libro->toArray());
		
	}
	
	/**
	 * Función que obtiene un registro de la tabla materia, mediante el idMateria
	 * @param $idMateria
	 * @return array | null
	 */
	public function obtenerLibro($idLibro) {
		$tablaLibro = $this->tablaLibro;
		$where = $tablaLibro->getAdapter()->quoteInto("idLibro = ?", $idLibro);
		$rowLibro = $tablaLibro->fetchRow($where);
		
		$modelLibro = new Biblioteca_Model_Libro($rowLibro->toArray());
		/*
		foreach ($rowLibros as $rowLibro) {
			$modelLibro = new Biblioteca_Model_Libro($rowLibro->toArray());
			$modelLibros[] = $modelLibro;
		}
		*/
		return $modelLibro;
	}
	
	

	
	public function prestamoLibro($libro,$registro) {}
	public function devolverLibro($libro,$registro) {}
	public function liberarLibro($libro,$causa,$destino) {}
	
	/**
	 * Actualiza el registro de la tabla Libro especificado por idLibro
	 * @param $idLibro - el id de libro a modificar
	 * @param $datos - array de datos a modificar en el registro.
	 */
	public function actualizarLibro($idLibro, array $datos){
		$tablaLibro = $this->tablaLibro;
		$where = $tablaLibro->getAdapter()->quoteInto("idLibro=?", $idLibro);
		$tablaLibro->update($datos, $where);
	}
	
	/**
	 * Obtenemos un array de models Libro
	 */
	public function getAllLibros(){
		$tablaLibro = $this->tablaLibro;
		$rowsLibro = $tablaLibro->fetchAll();
		if(!is_null($rowsLibro)){
			$arrLibros = $rowsLibro->toArray();
			$arrModelLibro = array();
			foreach ($arrLibros as $arrLibro) {
				$modelLibro = new Biblioteca_Model_Libro($arrLibro);
				$arrModelLibro[] = $modelLibro;
			}
			return $arrModelLibro;
		}else{
			return array();
		}
		
	}
}

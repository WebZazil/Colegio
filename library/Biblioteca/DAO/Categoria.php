<?php

/**
 * Clase que opera sobre la categoria de los libros en la biblioteca
 * @author Alizon Fernanda Diaz
 * @copyright 2016
 * @version 1.0.0
 */
class Biblioteca_DAO_Categoria implements Biblioteca_Interfaces_ICategoria{
		
	private $tablaCategoria;
	
		
	function __construct() {
		$dbAdapter = Zend_Registry::get("dbgenerale");
		
		$this->tablaCategoria = new Biblioteca_Model_DbTable_Categoria(array("db"=>$dbAdapter));
	}
	
	/**
	 * Funcion que agrega una categoria de los catalogos de libros 
	 * @param $categoria Biblioteca_Models_Categoria
	 */
	 public function agregarCategoria(Biblioteca_Models_Categoria $categoria){
	 	
		$tablaCategoria = $this->tablaCategoria;
		$tablaCategoria->insert($categoria->toArray());
		
	 }
	 
	 
}
?>
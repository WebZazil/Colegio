<?php
    /**
	 * Clase que opera sobre la categoría de los libros en la biblioteca
	 * @author Alizon Fernanda Díaz
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
	 * Función que agrega una categoria de los catalogos de libros 
	 * @param $categoria categoria a agregar
	 */
	 
	 public function agregarCategoria(Biblioteca_Model_Categoria $categoria){
	 	
		$tablaCategoria = $this->tablaCategoria;
		$tablaCategoria->insert($categoria->toArray());
		
	 }
	 
	 
}
?>
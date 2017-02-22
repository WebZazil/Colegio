<?php
/**
 * Interface que define operaciones sobre libros
 * @author Alizon Fernanda Díaz
 * @copyright 2016
 * @version 1.0.0
 */
interface Biblioteca_Interfaces_ILibro {
	
	public function agregarLibro(Biblioteca_Model_Libro $libro);
	public function obtenerLibro($idLibro);
	public function prestamoLibro($libro,$registro);
	public function devolverLibro($libro,$registro);
	public function liberarLibro($libro,$causa,$destino);
	public function actualizarLibro($idLibro, array $datos);
	
	public function getAllLibros();
	
}

<?php
    /**
 * Interface que define operaciones sobre libros
 * @author Alizon Fernanda Díaz
 * @copyright 2016
 * @version 1.0.0
 */
interface Biblioteca_Interfaces_IMateria{
	
	public function agregarMateria(Biblioteca_Model_Materia $materia);
	
	public function obtenerMateriasB(Biblioteca_Model_Materia $materia);
	public function obtenerMateriaB($idMateria);
	
	public function getAllMaterias();
	public function getLibrosByIdMateria($idMateria);
}
	

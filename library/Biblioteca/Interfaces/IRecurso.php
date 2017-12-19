<?php

/*
 * 
 */
 
 interface Biblioteca_Interfaces_IRecurso{
 	
 	public function agregarRecurso(Biblioteca_Model_Recurso $recurso);
	
	public function getAllRecursos();
	
	public function getAllTableRecursos();
	
	//public function buscar($consulta);
	
		
	
 }
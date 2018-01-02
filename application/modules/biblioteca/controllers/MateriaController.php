<?php

class Biblioteca_MateriaController extends Zend_Controller_Action
{
    private $materiaDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $this->materiaDAO = new Biblioteca_Data_DAO_Material($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
    }

    public function altaAction()
    {
        // action body
        $request = $this->getRequest();
		
		$formulario = new Biblioteca_Form_AltaMateria();
		
		
        if($request->isGet()){
        	$this->view->formulario = $formulario;
        }elseif($request->isPost()){
        	if($formulario->isValid($request->getPost())){
        		$datos = $formulario->getValues();
				//print_r($datos);
				
				$materia = new Biblioteca_Model_Materia($datos);
				//print_r($materia->toArray());
				
				try{
					$this->materiaDAO->agregarMateria($materia);
					$this->view->messageSuccess = "La materia: <strong>".$materia->getMateria()."</strong> ha sido agregado correctamente !!";
				}catch(Exception $ex){
					$this->view->messageFail = "La materia: <strong>".$materia->getMateria()."</strong> no ha sido agregado. Error: <strong>".$ex->getMessage()."<strong>";
				}
				
        	}
        }
    }

    public function consultalibroAction()
    {
        // action body
        
            $request = $this->getRequest();
        $formulario = new Biblioteca_Form_ConsultaLibroPorMateria;
		$this->view->formulario = $formulario;
		
		if($request->isPost()){
			if($formulario->isValid($request->getPost())){
				$datos = $formulario->getValues();
				$libros = $this->materiaDAO->getLibrosByIdMateria($datos["idMateria"]);
				$this->view->libros = $libros;
				print_r($libros);
				
				$librosArray = $libros;
		
       
        
			}
		}
    }


}






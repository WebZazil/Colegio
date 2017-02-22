<?php
/**
 * 
 */
class Biblioteca_Forms_FormSteps extends Zend_Form {
	
	private $formDecorators;
	private $subformDecorators;
	private $telementsDecorators;
	private $belementsDecorators;
	private $headerDecorator;
	
	function __construct() {
		$this->headerDecorator = new Util_Html_Form_Decorator_HtmlHeader();
		
		$this->formDecorators = array(
			'FormElements',
			'HtmlTag',
			'Form'
		);
		
		$this->subformDecorators = array(
			//array(array('header'=>'HtmlTag'),array('tag'=>'h3')),
			'FormElements',
			array('HtmlTag',array('tag'=>'section')),
			$this->headerDecorator
			//array('Fieldset', array('tag' => 'section'))
		);
		
		$this->telementsDecorators = array(
			array('ViewHelper', array('tag')),
			array('Label', array('tag'))
		);
		
		$this->setDecorators($this->formDecorators);
		$this->setSubFormDecorators($this->subformDecorators);
		$this->setElementDecorators($this->telementsDecorators);
	}
	
	public function getMFormDecorators() {
		return $this->formDecorators;
	}
	
	public function getMSubFormDecorators() {
		return $this->subformDecorators;
	}
	
	public function getMTextElementDecorators() {
		return $this->telementsDecorators;
	}
	
	public function getMButtonElementDecorators() {
		return $this->belementsDecorators;
	}
	
}

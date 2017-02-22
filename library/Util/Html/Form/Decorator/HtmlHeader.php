<?php
/**
 * 
 */
class Util_Html_Form_Decorator_HtmlHeader extends Zend_Form_Decorator_Abstract {
	
	
	
	/*function __construct($argument) {
		
	}*/
	
	//protected $_format = '<label for="%s">%s</label>';
	protected $_format = '<h3>%s</h3>';
 
    public function render($content)
    {
        $element = $this->getElement();
        $id   = htmlentities($element->getId());
        //$label   = htmlentities($element->getlabel());
		$title = htmlentities($element->getLegend());
   
 
        $markup  = sprintf($this->_format, $title);
		/*
		$placement = $this->getPlacement();
		$seperator = $this->getSeparator();
		
		switch ($placement) {
			case self::PREPEND:
				return	$markup.$seperator.$content;
				
			case self::APPEND:
				return $content.$seperator.$markup;
				
				break;
			
			default:
				return $content. $seperator. $markup;
				
		}
		*/
		return $markup.$content;
    }
	
	

}

	

		


<?php
/**
 * 
 */
class App_Util_Encode {
	
	function __construct() {
		
	}
	
	/**
	 * Itera sobre un array asociativo de n dimension 
	 * codificando los valores a UTF-8.
	 */
	public function utf8_string_array_encode(&$theArray) {
		$func = function(&$value,&$key){
	        if(is_string($value)){
	            $value = utf8_encode($value);
	        }
	        if(is_string($key)){
	            $key = utf8_encode($key);
	        }
	        if(is_array($value)){
	            utf8_string_array_encode($value);
	        }
	    };
		
		array_walk($theArray,$func);
		return $theArray;
	}
	
	function utf8_encode_deep(&$input) {
	    if (is_string($input)) {
	        $input = utf8_encode($input);
	    } else if (is_array($input)) {
	        foreach ($input as &$value) {
	            utf8_encode_deep($value);
	        }
	
	        unset($value);
	    } else if (is_object($input)) {
	        $vars = array_keys(get_object_vars($input));
	
	        foreach ($vars as $var) {
	            utf8_encode_deep($input->$var);
	        }
    	}
	}
}

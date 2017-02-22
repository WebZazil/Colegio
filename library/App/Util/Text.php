<?php
/**
 * 
 */
class App_Util_Text {
	
	function __construct() {
		
	}
	/**
	 * Normalize a $utf8 string to ascii text
	 * Changes characters like á to a 
	 */
	public function toAsciiText($utf8Str) {
		$datos = array();
		$search = "";
		$replace = "";
		
		$newString = str_replace($search, $replace, $utf8Str);
		return $newString;
	}
	
	public function normalize_special_characters( $str ) {
	    # Quotes cleanup
	    $str = ereg_replace( chr(ord("`")), "'", $str );        # `
	    $str = ereg_replace( chr(ord("´")), "'", $str );        # ´
	    $str = ereg_replace( chr(ord("„")), ",", $str );        # „
	    $str = ereg_replace( chr(ord("`")), "'", $str );        # `
	    $str = ereg_replace( chr(ord("´")), "'", $str );        # ´
	    $str = ereg_replace( chr(ord("“")), "\"", $str );        # “
	    $str = ereg_replace( chr(ord("”")), "\"", $str );        # ”
	    $str = ereg_replace( chr(ord("´")), "'", $str );        # ´
	
	    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
	                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
	                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
	                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
	                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
	    $str = strtr( $str, $unwanted_array );
		
		//print_r($str."<br />");
	
	    # Bullets, dashes, and trademarks
	    $str = ereg_replace( chr(149), "&#8226;", $str );    # bullet •
	    $str = ereg_replace( chr(150), "&ndash;", $str );    # en dash
	    $str = ereg_replace( chr(151), "&mdash;", $str );    # em dash
	    $str = ereg_replace( chr(153), "&#8482;", $str );    # trademark
	    $str = ereg_replace( chr(169), "&copy;", $str );    # copyright mark
	    $str = ereg_replace( chr(174), "&reg;", $str );        # registration mark
	
	    return $str;
	}
}

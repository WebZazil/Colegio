<?php
/**
 * 
 */
class Encuesta_Util_Text {
	
	function __construct() {
		
	}
    
    public function cleanString($text) {
        $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        );
        
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
    
    /**
     * 
     */
    public function replaceHTMLSpecialChars($string) {
        
        $specialCodes = array(
            'u00c3u0080' => 'À',
            'u00c3u0081' => 'Á',
            'u00c3u0082' => 'Â',
            'u00c3u0083' => 'Ã',
            'u00c3u0084' => 'Ä',
            'u00c3u0085' => 'Å',
            'u00c3u0086' => 'Æ',
            
            'u00c3u00a0' => 'à',
            'u00c3u00a1' => 'á',
            'u00c3u00a2' => 'â',
            'u00c3u00a3' => 'ã',
            'u00c3u00a4' => 'ä',
            'u00c3u00a5' => 'å',
            'u00c3u00a6' => 'æ',
            
            'u00c3u0087' => 'Ç',
            'u00c3u00a7' => 'ç',
            'u00c3u0090' => 'Ð',
            'u00c3u00b0' => 'ð',
            
            'u00c3u0088' => 'È',
            'u00c3u0089' => 'É',
            'u00c3u008a' => 'Ê',
            'u00c3u008b' => 'Ë', 
            
            'u00c3u00a8' => 'è',
            'u00c3u00a9' => 'é',
            'u00c3u00aa' => 'ê',
            'u00c3u00ab' => 'ë',
            
            'u00c3u008c' => 'Ì',
            'u00c3u008d' => 'Í',
            'u00c3u008e' => 'Î',
            'u00c3u008f' => 'Ï',
            
            'u00c3u00ac' => 'ì',
            'u00c3u00ad' => 'í',
            'u00c3u00ae' => 'î',
            'u00c3u00af' => 'ï',
            
            'u00c3u0091' => 'Ñ',
            'u00c3u00b1' => 'ñ',
            
            'u00c3u0092' => 'Ò',
            'u00c3u0093' => 'Ó',
            'u00c3u0094' => 'Ô',
            'u00c3u0095' => 'Õ',
            'u00c3u0096' => 'Ö',
            'u00c3u0098' => 'Ø',
            'u00c3u0092' => 'Œ',
            
            'u00c3u00b2' => 'ò',
            'u00c3u00b3' => 'ó',
            'u00c3u00b4' => 'ô',
            'u00c3u00b5' => 'õ',
            'u00c3u00b6' => 'ö',
            'u00c3u00b8' => 'ø',
            'u00c3u0093' => 'œ',
            
            'u00c3u0099' => 'Ù',
            'u00c3u009a' => 'Ú',
            'u00c3u009b' => 'Û',
            'u00c3u009c' => 'Ü',
            
            'u00c3u00b9' => 'ù',
            'u00c3u00ba' => 'ú',
            'u00c3u00bb' => 'û',
            'u00c3u00bc' => 'ü',
            
            'u00c3u009d' => 'Ý',
            'u00c3u00b8' => 'Ÿ',
            'u00c3u00bd' => 'ý',
            'u00c3u00bf' => 'ÿ',
            
        );
        
        //return preg_replace(array_keys($specialCodes), array_values($specialCodes), $string);
        
        return str_replace(array_keys($specialCodes), array_values($specialCodes), $string);
        //preg_re
    }
    
}

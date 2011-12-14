<?php

/**
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionCMS/License.txt
 *
 * @category   webEdition
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
 * Util Functions
 *
 * all functions in this class are static! Please use it in static form:
 *    we_util::function_name();
 *
 *
 * @static
 */
class we_util{
	/**
	 * Searches a string for matches to the regular expressions given in pattern
	 *
	 * @static
	 * @access public
	 *
	 * @param array pattern Array of patterns
	 * @param string string
	 */
	/* function eregi_array($pattern,$string){
	  foreach($pattern as $reg){
	  if(eregi($reg,$string)){
	  return true;
	  }
	  }
	  return false;
	  } */

	/**
	 * Formates a number with a country specific format into computer readable format.
	 * Returns the formated number.
	 *
	 * @static
	 * @access public
	 *
	 * @param mixed number
	 */
	static function std_numberformat($number){
		if(strpos($number, 'E')){	//  when number is too big, it is shown with E+xx
			$number = number_format($number, 2, '.', '');
		}
		if(preg_match('|.*,[0-9]*$|', $number)){ // deutsche schreibweise
			$umschreib = ereg_replace('(.*),([0-9]*)$', '\1.\2', $number);
			$pos = strrpos($number, ",");
			$vor = str_replace(".", "", substr($umschreib, 0, $pos));
			$number = $vor . substr($umschreib, $pos, strlen($umschreib) - $pos);
		} else if(preg_match('|.*\.[0-9]*$|', $number)){ // engl schreibweise
			$pos = strrpos($number, ".");
			$vor = substr($number, 0, $pos);
			$vor = ereg_replace('[,\.]', '', $vor);
			$number = $vor . substr($number, $pos, strlen($number) - $pos);
		} else{
			$number = ereg_replace('[,\.]', '', $number);
		}
		return $number;
	}

	/**
	 * Converts all windows and mac newlines from string to unix newlines
	 * Returns the converted String.
	 *
	 * @static
	 * @access public
	 *
	 * @param mixed number
	 */
	static function cleanNewLine($string){
		return str_replace(array("\n\r","\r\n","\r"),"\r",$string);
	}

	/**
	 * Removes from string all newlines and converts all <br> to newlines
	 * Returns the converted String.
	 *
	 * @static
	 * @access public
	 *
	 * @param mixed number
	 */
	static function br2nl($string){
		$string = str_replace(array("\n","\r"), '', $string);
		return eregi_replace("<br ?/?>", "\n", $string);
	}

	static function rmPhp($in){
		$out = '';
		$starttag = strpos($in, '<?');
		if($starttag === false)
			return $in;
		$lastStart = 0;
		while(!($starttag === false)) {
			$endtag = strpos($in, '?>', $starttag);
			$out .= substr($in, $lastStart, ($starttag - $lastStart));
			$lastStart = $endtag + 2;
			$starttag = strpos($in, '<?', $lastStart);
		}
		if($lastStart < strlen($in))
			$out .= substr($in, $lastStart, (strlen($in) - $lastStart));
		return $out;
	}

	static function getGlobalPath(){
		if(isset($GLOBALS['WE_MAIN_DOC']) && isset($GLOBALS['WE_MAIN_DOC']->Path)){
			return $GLOBALS['WE_MAIN_DOC']->Path;
		} else{
			return '';
		}
	}

	static function html2uml($text){
		return corretUml(html_entity_decode($text));
	}

}

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
function we_tag_ifVar($attribs){
	if(($foo = attributFehltError($attribs, "name", "ifVar"))){
		print($foo);
		return false;
	}
	if(($foo = attributFehltError($attribs, "match", "ifVar", true))){
		print($foo);
		return false;
	}

	$match = weTag_getAttribute("match", $attribs);
	$name = weTag_getAttribute("_name_orig", $attribs);
	$type = weTag_getAttribute("type", $attribs);
	$operator = weTag_getAttribute("operator", $attribs);

	$matchArray = makeArrayFromCSV($match);
	$_size = sizeof($matchArray);

	switch($type){
		case "customer" :
		case "sessionfield" :
			if($_size == 1 && $operator != '' && isset($_SESSION["webuser"][$name])){
				switch($operator){
					case "equal":
						return $_SESSION["webuser"][$name] == $match;
					case "less":
						return $_SESSION["webuser"][$name] < $match;
					case "less|equal":
						return $_SESSION["webuser"][$name] <= $match;
					case "greater":
						return $_SESSION["webuser"][$name] > $match;
					case "greater|equal":
						return $_SESSION["webuser"][$name] >= $match;
					case "contains":
						return (strpos($_SESSION["webuser"][$name], $match) !== false);
				}
			} else{
				return (isset($_SESSION["webuser"][$name]) && in_array($_SESSION["webuser"][$name], $matchArray));
			}
		case "global" :
			if($_size == 1 && $operator != '' && isset($GLOBALS[$name])){
				switch($operator){
					case "equal":
						return $GLOBALS[$name] == $match;
					case "less":
						return $GLOBALS[$name] < $match;
					case "less|equal":
						return $GLOBALS[$name] <= $match;
					case "greater":
						return $GLOBALS[$name] > $match;
					case "greater|equal":
						return $GLOBALS[$name] >= $match;
					case "contains":
						return (strpos($GLOBALS[$name], $match) !== false);
				}
			} else{
				return (isset($GLOBALS[$name]) && in_array($GLOBALS[$name], $matchArray));
			}
		case "request" :
			if(isset($_REQUEST[$name])){
				if($_size == 1 && $operator != '' && isset($_REQUEST[$name])){
					switch($operator){
						case "equal":
							return $_REQUEST[$name] == $match;
						case "less":
							return $_REQUEST[$name] < $match;
						case "less|equal":
							return $_REQUEST[$name] <= $match;
						case "greater":
							return $_REQUEST[$name] > $match;
						case "greater|equal":
							return $_REQUEST[$name] >= $match;
						case "contains":
							return (strpos($_REQUEST[$name], $match) !== false);
					}
				} else{
					return (isset($_REQUEST[$name]) && in_array($_REQUEST[$name], $matchArray));
				}
			} else{
				return false;
			}
		case "post" :
			if(isset($_POST[$name])){
				if($_size == 1 && $operator != '' && isset($_POST[$name])){
					switch($operator){
						case "equal":
							return $_POST[$name] == $match;
						case "less":
							return $_POST[$name] < $match;
						case "less|equal":
							return $_POST[$name] <= $match;
						case "greater":
							return $_POST[$name] > $match;
						case "greater|equal":
							return $_POST[$name] >= $match;
						case "contains":
							return (strpos($_POST[$name], $match) !== false);
					}
				} else{
					return (isset($_POST[$name]) && in_array($_POST[$name], $matchArray));
				}
			} else{
				return false;
			}
		case "get" :
			if(isset($_GET[$name])){
				if($_size == 1 && $operator != '' && isset($_GET[$name])){
					switch($operator){
						case "equal":
							return $_GET[$name] == $match;
						case "less":
							return $_GET[$name] < $match;
						case "less|equal":
							return $_GET[$name] <= $match;
						case "greater":
							return $_GET[$name] > $match;
						case "greater|equal":
							return $_GET[$name] >= $match;
						case "contains":
							return (strpos($_GET[$name], $match) !== false);
					}
				} else{
					return (isset($_GET[$name]) && in_array($_GET[$name], $matchArray));
				}
			} else{
				return false;
			}
		case "session" :
			if(isset($_SESSION[$name])){
				if($_size == 1 && $operator != '' && isset($_SESSION[$name])){
					switch($operator){
						case "equal":
							return $_SESSION[$name] == $match;
						case "less":
							return $_SESSION[$name] < $match;
						case "less|equal":
							return $_SESSION[$name] <= $match;
						case "greater":
							return $_SESSION[$name] > $match;
						case "greater|equal":
							return $_SESSION[$name] >= $match;
						case "contains":
							return (strpos($_SESSION[$name], $match) !== false);
					}
				} else{
					return (isset($_SESSION[$name]) && in_array($_SESSION[$name], $matchArray));
				}
			} else{
				return false;
			}
		case "property" :
			$docAttr = weTag_getAttribute("doc", $attribs);
			$doc = we_getDocForTag($docAttr, true);
			$var = $doc->$name;
			if($_size == 1 && $operator != '' && isset($var)){
				switch($operator){
					case "equal":
						return $var == $match;
					case "less":
						return $var < $match;
					case "less|equal":
						return $var <= $match;
					case "greater":
						return $var > $match;
					case "greater|equal":
						return $var >= $match;
					case "contains":
						return (strpos($var, $match) !== false);
				}
			} else{
				return in_array($var, $matchArray);
			}
		case "document" :
		default :
			$docAttr = weTag_getAttribute("doc", $attribs);
			$doc = we_getDocForTag($docAttr, true);
			if($_size == 1 && $operator != ''){
				switch($operator){
					case "equal":
						return $doc->getElement($name) == $match;
					case "less":
						return $doc->getElement($name) < $match;
					case "less|equal":
						return $doc->getElement($name) <= $match;
					case "greater":
						return $doc->getElement($name) > $match;
					case "greater|equal":
						return $doc->getElement($name) >= $match;
					case "contains":
						return (strpos($doc->getElement($name), $match) !== false);
				}
			} else{
				return in_array($doc->getElement($name), $matchArray);
			}
	}
}

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
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
 * class forerror_reporting, uses the javascript function showmessage in
 * webEdition.php
 *
 */
abstract class we_message_reporting{
// constants for messaging!
// these are binray checked like permissions in unix, DON'T change indexes

	const WE_MESSAGE_INFO = -1;
	const WE_MESSAGE_FRONTEND = -2;
	const WE_MESSAGE_NOTICE = 1;
	const WE_MESSAGE_WARNING = 2;
	const WE_MESSAGE_ERROR = 4;

	/**
	 * returns js-call for the showMessage function
	 *
	 * @param string $message
	 * @param integer $priority
	 * @param boolean $isJsMsg
	 * @return string
	 */
	public static function getShowMessageCall($message, $priority, $isJsMsg = false, $isOpener = false){
		$message = $isJsMsg ? $message : '"' . self::prepareMsgForJS($message) . '"';
		switch($priority){
			case self::WE_MESSAGE_INFO:
			case self::WE_MESSAGE_FRONTEND:
				return 'alert(' . $message . ');';
			default:
				return ($isOpener ? 'top.opener.' : '') . 'top.we_showMessage(' . $message . ', ' . $priority . ', window);';
		}
	}

	public static function prepareMsgForJS($message){
		return strtr($message, array(
			"\n" => '###NL###',
			'\n' => '###NL###',
			'\\' => '\\\\',
			'"' => '\\"',
			'###NL###' => '\n'
		));
	}

	public static function jsString(){
		return we_html_element::jsElement('
var we_string_message_reporting_notice = "' . g_l('alert', '[notice]') . '";
var we_string_message_reporting_warning = "' . g_l('alert', '[warning]') . '";
var we_string_message_reporting_error = "' . g_l('alert', '[error]') . '";
');
	}

	public static function getJSLevelVar(){
		return
			'var WE_MESSAGE_NOTICE = ' . we_message_reporting::WE_MESSAGE_NOTICE . ';
	var WE_MESSAGE_WARNING = ' . we_message_reporting::WE_MESSAGE_WARNING . ';
	var WE_MESSAGE_ERROR = ' . we_message_reporting::WE_MESSAGE_ERROR . ';';
	}

}

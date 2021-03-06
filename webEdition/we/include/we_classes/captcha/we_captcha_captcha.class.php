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
abstract class we_captcha_captcha{

	/**
	 * display the image
	 *
	 * @return void
	 */
	static function display(we_captcha_image $image, $type = "gif"){
		list($type, $data) = self::get($image, $type);

		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Content-type: ' . $type);
		echo $data;
	}

	static function get(we_captcha_image $image, $type = "gif"){
		$code = '';
		$im = $image->get($code);
		// save the code to the memory
		self::save($code);

		ob_start();
		$type = '';
		switch($type){
			case 'jpg':
				$type = 'image/jpeg';
				imagejpeg($im);
				imagedestroy($im);
				break;
			case 'png':
				$type = 'image/png';
				imagepng($im);
				imagedestroy($im);
				break;
			case 'gif':
			default:
				$type = 'image/gif';
				imagegif($im);
				imagedestroy($im);
				break;
		}
		return array($type, ob_get_clean());
	}

	static function check($captcha, $type = 'captcha'){
		static $valid = array();
		if(isset($valid[$captcha])){
			return true;
		}
		$db = $GLOBALS['DB_WE'];
		self::cleanup($db);
		$db->query('DELETE FROM ' . CAPTCHA_TABLE . ' WHERE IP=x\'' . bin2hex(inet_pton(strstr($_SERVER['REMOTE_ADDR'], ':') ? $_SERVER['REMOTE_ADDR'] : '::ffff:' . $_SERVER['REMOTE_ADDR'])) . '\' AND typ="' . $db->escape($type) . '" AND BINARY code="' . $db->escape($captcha) . '" AND agent=x\'' . $db->escape(md5($_SERVER['HTTP_USER_AGENT']), true) . '\'', '', $db);
		if($db->affected_rows()){
			$valid[$captcha] = true;
			return true;
		}
		return false;
	}

	static function cleanup(we_database_base $db){
		$db->query('DELETE FROM ' . CAPTCHA_TABLE . ' WHERE valid<NOW()');
	}

	/**
	 * Save the Captcha Code
	 *
	 * @param string $captcha
	 * @return void
	 */
	static function save($captcha, $type = 'captcha', $validity = 1800){
		$db = $GLOBALS['DB_WE'];
		self::cleanup($db);
//FIMXE: make IP bin save
		$db->query('REPLACE INTO ' . CAPTCHA_TABLE . ' SET ' . we_database_base::arraySetter(array(
				'IP' => inet_pton(strstr($_SERVER['REMOTE_ADDR'], ':') ? $_SERVER['REMOTE_ADDR'] : '::ffff:' . $_SERVER['REMOTE_ADDR']),
				'agent' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : sql_function('x\'' . md5($_SERVER['HTTP_USER_AGENT']) . '\''),
				'typ' => $type,
				'code' => $captcha,
				'valid' => sql_function('NOW()+INTERVAL ' . $validity . ' SECOND'),
		)));
	}

}

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
abstract class we_captcha_captcha{

	/**
	 * display the image
	 *
	 * @return void
	 */
	static function display($image, $type = "gif"){

		$code = "";
		$im = $image->get($code);
		// save the code to the memory
		self::save($code);

		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');

		switch($type){
			case 'jpg':
				header("Content-type: image/jpeg");
				imagejpeg($im);
				imagedestroy($im);
				return;
			case 'png':
				header("Content-type: image/png");
				imagepng($im);
				imagedestroy($im);
				return;
			case 'gif':
			default:
				header('Content-type: image/gif');
				imagegif($im);
				imagedestroy($im);
				return;
		}
	}

	/**
	 * Clean the Memory
	 *
	 * @return boolean
	 */
	static function check($captcha){
		$db = new DB_WE();
		self::cleanup($db);
		$id = f('SELECT ID FROM ' . CAPTCHA_TABLE . ' WHERE IP="' . $db->escape($_SERVER['REMOTE_ADDR']) . '" AND code="' . $db->escape($captcha) . '" AND agent="' . $_SERVER['HTTP_USER_AGENT'] . '"', '', $db);

		if($id){
			$db->query('DELETE FROM ' . CAPTCHA_TABLE . ' WHERE ID=' . $id);
			return true;
		}
		return false;
	}

	static function cleanup(we_database_base $db){
		$db->query('DELETE FROM ' . CAPTCHA_TABLE . ' WHERE created < NOW()-INTERVAL 30 MINUTE');
	}

	/**
	 * Save the Captcha Code to the Memory
	 *
	 * @param string $captcha
	 * @return void
	 */
	function save($captcha){
		$db = new DB_WE();
		self::cleanup($db);

		$db->query('INSERT INTO ' . CAPTCHA_TABLE . ' SET ' . we_database_base::arraySetter(array(
				'IP' => $_SERVER['REMOTE_ADDR'],
				'agent' => $_SERVER['HTTP_USER_AGENT'],
				'code' => $captcha
		)));
	}

}

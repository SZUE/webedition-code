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

		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');

		switch($type){
			case 'jpg':
				header("Content-type: image/jpeg");
				imagejpeg($im);
				imagedestroy($im);
				break;
			case 'png':
				header("Content-type: image/png");
				imagepng($im);
				imagedestroy($im);
				break;
			case 'gif':
			default:
				header("Content-type: image/gif");
				imagegif($im);
				imagedestroy($im);
				break;
		}

		// save the code to the memory
		we_captcha_memory::save($code, we_captcha_captcha::getStorage());
	}

	/* end: save */

	/**
	 * Clean the Memory
	 *
	 * @return boolean
	 */
	static function check($captcha){

		return we_captcha_memory::isValid($captcha, we_captcha_captcha::getStorage());
	}

	/* end: check */

	/**
	 * get the filename
	 *
	 * @return boolean
	 */
	static function getStorage(){
		return TEMP_PATH . 'captchacodes.tmp';
	}

	/* end: check */
}

/* end: Class */

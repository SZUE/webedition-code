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
	 * if hook execution is enabled this function will be executed
	 * when deleting a document, template, object or class
	 * The array $param has all information about the respective document.
	 *
	 * IMPORTANT!
	 * Copy this file to the custom_hooks folder when doing any changes
	 * Files in the sample_hooks folder are not executed and are not update-safe and will be overwritten by the next webEdition update
	 *
	 * @param array $param
	 */
	function weCustomHook_delete($param) {
		$hookHandler=$param['hookHandler'];
		/*$obj=$param[0];
		switch(get_class($obj)){
		}*/

		/**
		 * e.g.:
		 *
		 * ob_start("error_log");
		 * print_r($param);
		 * ob_end_clean();
		 */


	}

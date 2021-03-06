<?php

/**
 * webEdition CMS
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
 * class for Services
 *
 * @category   app
 * @package none
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');

class toolfactory_service_Install extends we_app_service_AbstractCmd{

	public function getApplist(){
		global $metaInfo;
		$appName = Zend_Controller_Front::getInstance()->getParam('appName');
		$app_directory = dir(WE_APPS_PATH);
		$app_directory_string = WE_APPS_PATH;
		$apparray = array();
		while(false !== ($entry = $app_directory->read())){
			if($entry != "." && $entry != ".."){
				$path_parts = pathinfo($entry);
				if(isset($path_parts['extension']) && $path_parts['extension'] === 'tgz'){
					$appdata = explode('_', $path_parts['filename']);
					if(isset($appdata[0]) && $appdata[1]){
						$app = array('source' => $app_directory_string . '/' . $entry, 'classname' => $appdata[0], 'version' => $appdata[1]);
						$apparray[] = $app;
					}
				}
			}
		}

		if(is_dir($_SERVER['DOCUMENT_ROOT'] . '/appinstall')){
			$app_directory = dir($_SERVER['DOCUMENT_ROOT'] . '/appinstall');
			$app_directory_string = $_SERVER['DOCUMENT_ROOT'] . '/appinstall';
			while(false !== ($entry = $app_directory->read())){
				if($entry != "." && $entry != ".."){
					$path_parts = pathinfo($entry);
					if(isset($path_parts['extension']) && $path_parts['extension'] === 'tgz'){
						$appdata = explode('_', $path_parts['filename']);
						if(isset($appdata[0]) && $appdata[1]){
							$app = array('source' => $app_directory_string . '/' . $entry, 'classname' => $appdata[0], 'version' => $appdata[1]);
							$apparray[] = $app;
						}
					}
				}
			}
		}
		return $apparray;
	}

}

<?php

/**
 * webEdition SDK
 *
 * This source is part of the webEdition SDK. The webEdition SDK is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU Lesser General Public License can be found at
 * http://www.gnu.org/licenses/lgpl-3.0.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionSDK/License.txt
 *
 *
 * @category   we
 * @package none
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * class for cmd service
 *
 * @category   we
 * @package none
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_service_Cmd extends we_service_AbstractService{

	/**
	 * create model
	 *
	 * @param array $args
	 * @return NULL
	 */
	public function createModel($args){
		if(!isset($args[0])){
			throw new we_service_Exception('No model class name set!');
		}
		try{
			$model = new $args[0]();
			if(isset($args[1])){
				$model->load(($args[1]));
			}
			return $model;
		} catch (Exception $e){
			throw new we_service_Exception('Error creating new model: ' . $e->getMessage());
		}
		return NULL;
	}

}

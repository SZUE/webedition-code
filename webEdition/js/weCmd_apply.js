/* global WE */

/**
 * webEdition SDK
 *
 * webEdition CMS
 * $Rev: 12829 $
 * $Author: mokraemer $
 * $Date: 2016-09-19 18:48:39 +0200 (Mo, 19 Sep 2016) $
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
 * @package    we_ui
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));

	if(parent.we_cmd){
		parent.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	} else if(top.we_cmd){
		top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	} else if(top.opener.we_cmd){
		top.opener.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}
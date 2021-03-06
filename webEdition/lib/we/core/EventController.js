/**
 * webEdition SDK
 *
 * webEdition CMS
 * $Rev$
 * $Author$
 * $Date$
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
 * @package    we_core
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
function we_core_EventController() {
	this.events = {};

	this.fire = function(eventName, data) {
		if (this.events[eventName] !== undefined) {
			this.events[eventName].fire(data);
		}
	};

	this.register = function(eventName, callbackFn, scope) {
		if (this.events[eventName] === undefined) {
			this.events[eventName] = new YAHOO.util.CustomEvent(eventName, scope, false, YAHOO.util.CustomEvent.FLAT);
		}
		this.events[eventName].subscribe(callbackFn, self);
	};

	this.unregister = function(eventName, callbackFn) {
		if (this.events[eventName] !== undefined) {
			this.events[eventName].unsubscribe(callbackFn);
		}
	};
}

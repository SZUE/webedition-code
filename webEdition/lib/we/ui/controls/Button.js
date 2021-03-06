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
 * @package    we_ui
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */


/**
 * Class for handling we_ui_controls_Button Element
 *
 * @category   we
 * @package    we_ui
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
we_ui_controls_Button = {};

/**
 * disables / enables Button element, hidden input element of button = submit and <a> tag of button=href
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return void
 */
we_ui_controls_Button.setDisabled = function (idOrObject, disabled)
{
	var element = idOrObject;
	if (typeof (element) != "object") {
		element = document.getElementById(idOrObject);
	}
	if (disabled) {
		element.className = "we_ui_controls_Disabled_Button";
		if (element.childNodes[0].className == "we_ui_controls_Clicked_Button_Left" || element.childNodes[0].className == "we_ui_controls_Button_Left") {
			element.childNodes[0].className = "we_ui_controls_Disabled_Button_Left";
			element.childNodes[1].className = "we_ui_controls_Disabled_Button_Middle";
			element.childNodes[2].className = "we_ui_controls_Disabled_Button_Right";
			var img = document.getElementById(element.id + "_img");
			if (img !== null && img.src.indexOf("Disabled.gif") === -1) {
				img.src = img.src.replace(/\.gif/, "Disabled.gif");
			}
		}

		if (document.getElementById("input_" + element.id)) {
			var input = document.getElementById("input_" + element.id);
			input.disabled = true;
		}
		if (document.getElementById("a_" + element.id)) {
			var a = document.getElementById("a_" + element.id);
			a.onclick = function () {
				return false;
			};
		}
		if (document.getElementById("table_" + element.id)) {
			var table = document.getElementById("table_" + element.id);
			table.className = "we_ui_controls_Disabled_Button_InnerTable";
		}

	} else {
		element.className = "we_ui_controls_Button";
		if (element.childNodes[0].className == "we_ui_controls_Clicked_Button_Left" || element.childNodes[0].className == "we_ui_controls_Disabled_Button_Left") {
			element.childNodes[0].className = "we_ui_controls_Button_Left";
			element.childNodes[1].className = "we_ui_controls_Button_Middle";
			element.childNodes[2].className = "we_ui_controls_Button_Right";
			var img = document.getElementById(element.id + "_img");
			if (img !== null && img.src.indexOf("Disabled.gif") === -1) {
				img.src = img.src.replace(/\Disabled.gif/, ".gif");
			}
		}
		if (document.getElementById("input_" + element.id)) {
			var input = document.getElementById("input_" + element.id);
			input.disabled = false;
		}
		if (document.getElementById("a_" + element.id)) {
			var a = document.getElementById("a_" + element.id);
			a.onclick = function () {
				return true;
			};
		}
		if (document.getElementById("table_" + element.id)) {
			var table = document.getElementById("table_" + element.id);
			table.className = "we_ui_controls_Button_InnerTable";
		}
	}
};

/**
 * marks the Button after mouseDown event as clicked
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return void
 */
we_ui_controls_Button.down = function (idOrObject)
{
	var element = idOrObject;
	if (typeof (element) != "object") {
		element = document.getElementById(idOrObject);
	}
	if (element.childNodes[0].className == "we_ui_controls_Clicked_Button_Left" || element.childNodes[0].className == "we_ui_controls_Button_Left") {
		if (element.className != "we_ui_controls_Disabled_Button") {
			element.className = "we_ui_controls_Clicked_Button";
			element.childNodes[0].className = "we_ui_controls_Clicked_Button_Left";
			element.childNodes[1].className = "we_ui_controls_Clicked_Button_Middle";
			element.childNodes[2].className = "we_ui_controls_Clicked_Button_Right";
		}
	}
};

/**
 * marks the Button after mouseOut event as default
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return void
 */
we_ui_controls_Button.out = function (idOrObject)
{
	var element = idOrObject;
	if (typeof (element) != "object") {
		element = document.getElementById(idOrObject);
	}
	if (element.childNodes[0].className == "we_ui_controls_Clicked_Button_Left" || element.childNodes[0].className == "we_ui_controls_Button_Left") {
		if (element.className != "we_ui_controls_Disabled_Button" && element.className != "we_ui_controls_Button") {
			element.className = "we_ui_controls_Button";
			element.childNodes[0].className = "we_ui_controls_Button_Left";
			element.childNodes[1].className = "we_ui_controls_Button_Middle";
			element.childNodes[2].className = "we_ui_controls_Button_Right";
		}
	}
};

/**
 * marks the Button after mouseUp event as default
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return boolean
 */
we_ui_controls_Button.up = function (idOrObject)
{
	var element = idOrObject;
	if (typeof (element) != "object") {
		element = document.getElementById(idOrObject);
	}
	if (element.className != "we_ui_controls_Disabled_Button") {
		we_ui_controls_Button.out(element);
		return true;
	}
	return false;
};

/**
 * hides the Button
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return void
 */
we_ui_controls_Button.hide = function (idOrObject)
{
	var element = idOrObject;
	if (typeof (element) != "object") {
		element = document.getElementById(idOrObject);
	}
	if (element !== null) {
		element.style.display = "none";
	}
};

/**
 * shows the Button
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return void
 */
we_ui_controls_Button.show = function (idOrObject)
{
	var element = idOrObject;
	if (typeof (element) != "object") {
		element = document.getElementById(idOrObject);
	}
	if (element !== null) {
		element.style.display = "";
	}
};

/**
 * checks if the Button is disabled
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return boolean
 */
we_ui_controls_Button.isDisabled = function (idOrObject)
{
	var element = idOrObject;
	if (typeof (element) != "object") {
		element = document.getElementById(idOrObject);
	}
	if (element !== null && element.className == "we_ui_controls_Disabled_Button") {
		return true
	}
	return false;
};

/**
 * checks if the Button is enabled
 *
 *@static
 *@param {object|string} idOrObject id or reference of button element
 *@return boolean
 */
we_ui_controls_Button.isEnabled = function (idOrObject)
{
	return !this.isDisabled(idOrObject);
};

/**
 * adds a Button
 *
 *@static
 *@param string buttonId id of button element
 *@param string buttonHTML html code of button element
 *@param string positionID id of element within the button should be added
 *@return void
 */
we_ui_controls_Button.addButton = function (buttonId, buttonHTML, positionID)
{
	var container = positionID;
	if (typeof (container) != "object") {
		container = document.getElementById(positionID);
	}
	if (container) {
		var desc = "added__ButtonDiv__";
		var count = 0;
		if (container.hasChildNodes) {
			var kids = container.childNodes;
			for (i = 0; i < kids.length; i++) {
				if (kids[i].id && kids[i].id.substring(0, 18) == desc) {
					count++;
				}
			}
		}
		var id = buttonId.replace(/__INDEX__/g, '');
		var mainDiv = document.createElement("DIV");
		mainDiv.id = desc + id;
		mainDiv.innerHTML = buttonHTML;
		container.appendChild(mainDiv);
	}

};